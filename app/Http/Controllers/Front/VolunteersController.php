<?php

namespace App\Http\Controllers\Front;

use App\Models\Activity;
use App\models\Meta;
use App\Models\Post;
use App\Models\State;
use App\Models\User;
use App\Providers\AppServiceProvider;
use App\Providers\EmailServiceProvider;
use App\Providers\SecKeyServiceProvider;
use App\Traits\TahaControllerTrait;
use Asanak\Sms\Facade\AsanakSms;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class VolunteersController extends Controller
{
    use TahaControllerTrait;

    private $registerSessionName = 'register_volunteer';
    private $currentRegisteringSessionName = 'current_registering';

    public function index()
    {
        $post = Post::findBySlug('volunteers-detail')->in(getLocale());
        if (!$post or !$post->exists) {
            return redirect(url_locale());
        }

        return view('front.volunteers.volunteers_info.main', compact('post'));
    }

    public function register_first_step(Requests\Front\Volunteer\VolunteerFirstStepRequest $request)
    {
        $checkResult = $this->checkCodeMelli($request->code_melli);
        if (!$checkResult['canRegister']) {
            return $checkResult['response'];
        }

        $input = $request->toArray();
        $user = User::selectBySlug($input['code_melli'], 'code_melli');

        // @TODO: verify "code_melli" with "name_first" and "name_last"
        $currentSession = session()->get($this->registerSessionName) ?: [];
        $currentSession[$request->code_melli] = [
            'verified' => true,
            'step'     => 1,
            'formData' => $request->except('_token', '_submit'),
        ];
        session()->put($this->registerSessionName, $currentSession);
        session()->put($this->currentRegisteringSessionName, $request->code_melli);

        if (setting()->ask('volunteer_exam')->gain()) {
            // @todo: redirect to exam page
        } else {
            $redirectUrl = route_locale('volunteer.register.step.final.get');
        }

        return $this->jsonFeedback(null, [
            'ok'       => 1,
            'message'  => trans('forms.feed.wait'),
            'redirect' => $redirectUrl,
        ]);
    }

    public function exam()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->isActive('volunteer') and $user->exam_passed_at) {
                return redirect('/manage');
            } elseif ($user->volunteer_status == 3 and $user->exam_passed_at) {
                return redirect('/');
            }
        } elseif (Session::get('volunteer_first_step')) {
            $data = Session::get('volunteer_first_step');
            $user = User::selectBySlug($data['code_melli'], 'code_melli');
            if ($user) {
                if ($user->volunteer_status == 2 or $user->volunteer_status > 3 or $user->volunteer_status < 0) {
                    return redirect('/');
                } elseif ($user->volunteer_status == 1) {
                    if (Carbon::parse($user->exam_passed_at)->diffInHours(Carbon::now()) < 24)
                        return redirect('/');
                } elseif ($user->volunteer_status == 3) {
                    if ($user->exam_passed_at)
                        return redirect('/');
                }
            }
        } else {
            return redirect('/');
        }

        $exam = Post::selector('tests')->limit(30)->inRandomOrder()->get();
        $volunteer = Post::findBySlug('volunteers_detail');
        if (!$volunteer or !$exam)
            return view('errors.404');

        $tests = [];

        foreach ($exam as $test) {
            $metas = $test->metas()
                ->whereIn('key', [
                    'option_wrong_1',
                    'option_wrong_2',
                    'option_wrong_3',
                    'option_true',
                ])
                ->inRandomOrder()->get()->toArray();

            $tests[] = [
                'question' => $test->text,
                'id'       => $test->id,
                'options'  => [
                    'A' => [$metas[0]['value'], $metas[0]['key']],
                    'B' => [$metas[1]['value'], $metas[1]['key']],
                    'C' => [$metas[2]['value'], $metas[2]['key']],
                    'D' => [$metas[3]['value'], $metas[3]['key']],
                ]
            ];

        }

        return view('site.volunteers.volunteers_exam.0', compact('volunteer', 'tests'));
    }

    public function register_second_step(Requests\site\volunteer\VolunteerSecondStepRequest $request)
    {
        $input = $request->toArray();

        $exam_question_count = decrypt($input['exam']);

        $data = [];
        $count = 0;
        $true_answer = 0;
        foreach ($input as $key => $value) {
            if (str_contains($key, 'answer-')) {
                $id = explode('-', $key);
                $data[$count]['id'] = $id[1];

                $answer = decrypt($value);
                if ($answer == 'option_true') {
                    $data[$count]['status'] = 2;
                    $data[$count]['select'] = $answer;
                    $true_answer++;
                } else {
                    $data[$count]['status'] = 1;
                    $data[$count]['select'] = $answer;
                }
                $count++;
            }
        }

        if (Auth::check()) {
            $store['code_melli'] = Auth::user()->code_melli;
        } else {
            $store = Session::pull('volunteer_first_step');
        }

        $store['exam_passed_at'] = Carbon::now()->toDateTimeString();
        $store['exam_sheet'] = json_encode($data);
        $store['exam_result'] = ceil(($true_answer * 100) / $exam_question_count);

        if ($store['exam_result'] >= 50) {
            $msg = trans('site.global.volunteer_exam_passed_ok') . AppServiceProvider::pd($store['exam_result']) . trans('site.global.volunteer_exam_passed_ok1');
            $volunteer_status = 2;
            $ajax_status = 1;
        } else {
            $msg = trans('site.global.volunteer_exam_passed_nok');
            $volunteer_status = 1;
            $ajax_status = 0;
        }

        $user = User::selectBySlug($store['code_melli'], 'code_melli');
        if (!$user) {
            $store['volunteer_status'] = $volunteer_status;
            $id = User::store($store);
            if ($id) {
                Session::put('volunteer_exam_passed', $id);
                if ($store['exam_result'] >= 50) {
                    $return = $this->jsonFeedback(null, [
                        'ok'           => $ajax_status,
                        'message'      => $msg . trans('site.global.volunteer_exam_passed_ok2'),
                        'redirect'     => url('/volunteers/final_step'),
                        'redirectTime' => 2000,
                    ]);
                } else {
                    $return = $this->jsonFeedback(null, [
                        'ok'      => $ajax_status,
                        'message' => $msg,
                    ]);
                }
            } else {
                $return = $this->jsonFeedback(null, [
                    'ok'      => 0,
                    'message' => trans('forms.feed.error'),
                ]);
            }
        } else {
            if ($user->isActive('volunteer')) {
                $update = [
                    'exam_passed_at' => $store['exam_passed_at'],
                    'exam_sheet'     => $store['exam_sheet'],
                    'exam_result'    => $store['exam_result'],
                    'id'             => $user->id,
                ];
                $id = User::store($update);
                if ($id) {
                    if ($store['exam_result'] >= 50) {
                        $return = $this->jsonFeedback(null, [
                            'ok'           => $ajax_status,
                            'message'      => $msg,
                            'redirect'     => url('/manage'),
                            'redirectTime' => 2000,
                        ]);
                    } else {
                        $return = $this->jsonFeedback(null, [
                            'ok'      => $ajax_status,
                            'message' => $msg,
                        ]);
                    }
                } else {
                    $return = $this->jsonFeedback(null, [
                        'ok'      => 0,
                        'message' => trans('forms.feed.error'),
                    ]);
                }
            } elseif ($user->volunteer_status == 3) {
                $update = [
                    'exam_passed_at' => $store['exam_passed_at'],
                    'exam_sheet'     => $store['exam_sheet'],
                    'exam_result'    => $store['exam_result'],
                    'id'             => $user->id,
                ];
                $id = User::store($update);
                if ($id) {
                    if ($store['exam_result'] >= 50) {
                        $return = $this->jsonFeedback(null, [
                            'ok'      => $ajax_status,
                            'message' => $msg . trans('site.global.volunteer_exam_passed_ok_volunteer_status_3'),
                        ]);
                    } else {
                        $return = $this->jsonFeedback(null, [
                            'ok'      => $ajax_status,
                            'message' => $msg,
                        ]);
                    }
                } else {
                    $return = $this->jsonFeedback(null, [
                        'ok'      => 0,
                        'message' => trans('forms.feed.error'),
                    ]);
                }
            } elseif ($user->volunteer_status == 1 or ($user->volunteer_status == 0 and $user->isActive('card'))) {
                $update = [
                    'exam_passed_at'   => $store['exam_passed_at'],
                    'exam_sheet'       => $store['exam_sheet'],
                    'exam_result'      => $store['exam_result'],
                    'volunteer_status' => $volunteer_status,
                    'id'               => $user->id,
                ];

                $id = User::store($update);
                Session::put('volunteer_exam_passed', $id);
                if ($id) {
                    if ($store['exam_result'] >= 50) {
                        $return = $this->jsonFeedback(null, [
                            'ok'           => $ajax_status,
                            'message'      => $msg . trans('site.global.volunteer_exam_passed_ok2'),
                            'redirect'     => url('/volunteers/final_step'),
                            'redirectTime' => 2000,
                        ]);
                    } else {
                        $return = $this->jsonFeedback(null, [
                            'ok'      => $ajax_status,
                            'message' => $msg,
                        ]);
                    }
                } else {
                    $return = $this->jsonFeedback(null, [
                        'ok'      => 0,
                        'message' => trans('forms.feed.error'),
                    ]);
                }
            } else {
                $return = $this->jsonFeedback(null, [
                    'ok'      => 0,
                    'message' => trans('forms.feed.error'),
                ]);
            }
        }

        return $return;
    }

    public function register_final_step()
    {
        $defaultFormData = [
            'name_first'      => '',
            'name_last'       => '',
            'gender'          => '',
            'name_father'     => '',
            'code_id'         => '',
            'code_melli'      => '',
            'birth_date'      => '',
            'birth_city'      => '',
            'marital'         => '',
            'edu_level'       => '',
            'edu_field'       => '',
            'edu_city'        => '',
            'email'           => '',
            'mobile'          => '',
            'tel_emergency'   => '',
            'home_city'       => '',
            'home_address'    => '',
            'home_tel'        => '',
            'home_postal'     => '',
            'job'             => '',
            'work_city'       => '',
            'work_address'    => '',
            'work_tel'        => '',
            'work_postal'     => '',
            'familiarization' => '',
            'motivation'      => '',
            'alloc_time'      => '',
            'activities'      => '',
        ];

        if (auth()->guest()) {
            // If user isn't logged in, form data should be read from session
            if (session()->has($this->currentRegisteringSessionName)) {
                $currentRegistering = session($this->currentRegisteringSessionName);
                $submittedIDs = session($this->registerSessionName);

                if (!array_key_exists($currentRegistering, $submittedIDs) or
                    !array_key_exists('formData', $submittedIDs[$currentRegistering])
                ) {
                    redirect(route_locale('volunteer.register.step.1.get'));
                }

                // @TODO: check exam passed if needed

                $currentValues = array_normalize($submittedIDs[$currentRegistering]['formData'], $defaultFormData);
            } else {
                return redirect(route_locale('volunteer.register.step.1.get'));
            }
        } else {
            if (user()->is_admin()) {
                // If user has logged in as a volunteer, rediredt to manage panel
                return redirect('manage');
            }
            if (user()->withDisabled()->is_admin()) {
                // If user has logged in as a volunteer, rediredt to manage panel
                return route_locale('volunteer.register.step.1.get');
            }

            // If user is logged in , form data should be read from user info
            $currentValues = array_normalize(user()->spreadMeta()->attributesToArray(), $defaultFormData);
        }

        $post = Post::findBySlug('volunteers-detail')->in(getLocale());
        if (!$post or !$post->exists) {
            return redirect(url_locale());
        }

        return view('front.volunteers.volunteer_register.main', compact('post', 'currentValues'));
    }

    public function register_final_step_submit(Requests\Front\Volunteer\VolunteerThirdStepRequest $request)
    {
        $homeState = State::find($request->home_city);
        $workState = State::find($request->work_city);
        $domain = $homeState->domain->slug;
        $modifyingData = [
            'home_province' => $homeState,
            'work_province' => $workState,
            'domain'        => $domain,
            'activities'    => implode(',', $request->activity),
        ];
        $request->merge($modifyingData);

        $userId = User::store($request, ['activity']);

        if ($userId) {
            $user = User::findBySlug($userId, 'id');
            $user->attachRole('volunteer_' . $domain, 1); // 1 status points to inactive volunteer
            $this->sendVerifications($user);

            $return = $this->jsonFeedback(null, [
                'ok'       => 1,
                'message'  => trans('front.volunteer_section.register_success'),
                'callback' => 'afterRegisterVolunteer()',
            ]);
        } else {
            $return = $this->jsonFeedback(null, [
                'ok'      => 0,
                'message' => trans('forms.feed.un_save'),
            ]);
        }

        return $return;
    }

    private function checkCodeMelli($codeMelli)
    {
        $user = User::findBySlug($codeMelli, 'code_melli');

        if ($user->exists) { // A user with the given "code_melli" exists.
            $loginLink = '<a href="' . route('login') . '">' . trans('front.messages.login') . '</a>';

            // @TODO: should think about order of conditions
            if ($user->is_admin()) { // This user is a volunteer
                $message = trans('front.messages.you_are_volunteer') . $loginLink;
                return [
                    'canRegister' => false,
                    'response'    => $this->jsonFeedback(null, [
                        'ok'      => true, // TODO: better be info
                        'message' => $message,
                    ]),
                ];
            } else if ($user->withDisabled()->is_admin()) { // This user id a blocked volunteer
                return [
                    'canRegister' => false,
                    'response'    => $this->jsonFeedback(null, [
                        'ok'      => true,
                        'message' => trans('front.messages.unable_to_register_card'),
                    ]),
                ];
            } else if ($user->is_an('card-holder')) { // This user has card
                $message = trans('front.messages.you_are_card_holder') . $loginLink;
                return [
                    'canRegister' => false,
                    'response'    => $this->jsonFeedback(null, [
                        'ok'      => true, // TODO: better be info
                        'message' => $message,
                    ]),
                ];
            }
        }

        return ['canRegister' => true];
    }

    /**
     * Send email and sms for verification after register volunteer.
     *
     * @param \App\Models\User $user
     */
    private function sendVerifications($user)
    {
        // Sending SMS
        if ($user->mobile) {
            $smsText = str_replace([
                ':name',
                ':site',
            ], [
                $user->full_name,
                setting()->ask('site_url')->gain(),
            ],
                trans('front.volunteer_section.register_success_message.sms'));

            $sendingSmsResult = AsanakSms::send($user->mobile, $smsText);
            $sendingSmsResult = json_decode($sendingSmsResult);
        }

        // Sending Mail
        if ($user->email) {
            $emailContent = str_replace([
                ':name',
                ':membershipNumber',
                ':site',
            ], [
                $user->full_name,
                $user->card_no,
                setting()->ask('site_url')->gain(),
            ],
                trans('front.organ_donation_card_section.register_success_message.email'));

            $sendingEmailResult = EmailServiceProvider::send($emailContent, $user['email'], trans('front.site_title'), trans('people.form.recover_password'), 'default_email');
        }
    }
}
