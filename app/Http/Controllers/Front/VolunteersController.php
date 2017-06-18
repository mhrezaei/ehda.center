<?php

namespace App\Http\Controllers\members;

use App\Models\Activity;
use App\models\Meta;
use App\Models\Post;
use App\Models\State;
use App\Models\User;
use App\Providers\AppServiceProvider;
use App\Providers\SecKeyServiceProvider;
use App\Traits\TahaControllerTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class VolunteersController extends Controller
{
    use TahaControllerTrait;

    public function index()
    {
        $captcha = SecKeyServiceProvider::getQuestion('fa');
        $volunteer = Post::findBySlug('volunteers_detail');
        if (! $volunteer)
            return view('errors.404');

        return view('site.volunteers.volunteers_info.0', compact('volunteer', 'captcha'));
    }

    public function register_first_step(Requests\site\volunteer\VolunteerFirstStepRequest $request)
    {
        $input = $request->toArray();
        $user = User::selectBySlug($input['code_melli'], 'code_melli');
        if ($user)
        {
            if ($user->isActive('volunteer') or $user->isActive('card'))
            {
                $return = $this->jsonFeedback(null, [
                    'redirect' => url('relogin'),
                    'ok' => 1,
                    'message' => trans('forms.feed.wait'),
                ]);
            }
            else if ($user->volunteer_status < 0 or $user->card_status < 0)
            {
                $return = $this->jsonFeedback(null, [
                    'ok' => 0,
                    'message' => trans('forms.feed.not_allowed'),
                ]);
            }
            else if ($user->volunteer_status == 1)
            {
                if ($user->exam_passed_at)
                {
                    if (Carbon::parse($user->exam_passed_at)->diffInHours(Carbon::now()) >= 24)
                    {
                        $return = $this->jsonFeedback(null, [
                            'ok' => 1,
                            'message' => trans('site.global.going_to_volunteer_exam_page'),
                            'redirect' => url('/volunteers/exam'),
                            'redirectTime' => 2000,
                        ]);
                    }
                    else
                    {
                        $return = $this->jsonFeedback(null, [
                            'ok' => 0,
                            'message' => trans('site.global.volunteer_exam_limit'),
                        ]);
                    }
                }
                else
                {
                    $return = $this->jsonFeedback(null, [
                        'ok' => 1,
                        'message' => trans('forms.feed.wait'),
                        'redirect' => url('/volunteers/exam'),
                        'redirectTime' => 2000,
                    ]);
                }
            }
            else if($user->volunteer_status == 2)
            {
                Session::put('volunteer_exam_passed', $user->id);
                $return = $this->jsonFeedback(null, [
                    'ok' => 1,
                    'message' => trans('forms.feed.wait'),
                    'redirect' => url('/volunteers/final_step'),
                ]);
            }
            else if($user->volunteer_status == 3)
            {
                if ($user->exam_passed_at)
                {
                    $return = $this->jsonFeedback(null, [
                        'ok' => 0,
                        'message' => trans('site.global.volunteer_account_not_confirm'),
                    ]);
                }
                else
                {
                    $return = $this->jsonFeedback(null, [
                        'ok' => 1,
                        'message' => trans('site.global.going_to_volunteer_exam_page'),
                        'redirect' => url('/volunteers/exam'),
                        'redirectTime' => 2000,
                    ]);
                }
            }
            else
            {
                $return = $this->jsonFeedback(null, [
                    'ok' => 1,
                    'message' => trans('site.global.going_to_volunteer_exam_page'),
                    'redirect' => url('/volunteers/exam'),
                    'redirectTime' => 2000,
                ]);
            }
        }
        else
        {
            $return = $this->jsonFeedback(null, [
                'ok' => 1,
                'message' => trans('site.global.going_to_volunteer_exam_page'),
                'redirect' => url('/volunteers/exam'),
                'redirectTime' => 2000,
            ]);
        }

        unset($input['_token']);
        unset($input['security']);
        unset($input['key']);
        Session::put('volunteer_first_step', $input);

        return $return;
    }

    public function exam()
    {
        if (Auth::check())
        {
            $user = Auth::user();
            if ($user->isActive('volunteer') and $user->exam_passed_at)
            {
                return redirect('/manage');
            }
            elseif ($user->volunteer_status == 3 and $user->exam_passed_at)
            {
                return redirect('/');
            }
        }
        elseif (Session::get('volunteer_first_step'))
        {
            $data = Session::get('volunteer_first_step');
            $user = User::selectBySlug($data['code_melli'], 'code_melli');
            if ($user)
            {
                if ($user->volunteer_status == 2 or $user->volunteer_status > 3 or $user->volunteer_status < 0)
                {
                    return redirect('/');
                }
                elseif ($user->volunteer_status == 1)
                {
                    if (Carbon::parse($user->exam_passed_at)->diffInHours(Carbon::now()) < 24)
                        return redirect('/');
                }
                elseif ($user->volunteer_status == 3)
                {
                    if ($user->exam_passed_at)
                        return redirect('/');
                }
            }
        }
        else
        {
            return redirect('/');
        }

        $exam = Post::selector('tests')->limit(30)->inRandomOrder()->get() ;
        $volunteer = Post::findBySlug('volunteers_detail');
        if (! $volunteer or ! $exam)
            return view('errors.404');

        $tests = [] ;

        foreach($exam as $test) {
            $metas = $test->metas()
                ->whereIn('key', [
                    'option_wrong_1',
                    'option_wrong_2',
                    'option_wrong_3',
                    'option_true',
                ])
                ->inRandomOrder()->get()->toArray() ;

            $tests[] = [
                'question' => $test->text ,
                'id' => $test->id ,
                'options' => [
                    'A' => [$metas[0]['value'] , $metas[0]['key']] ,
                    'B' => [$metas[1]['value'] , $metas[1]['key']] ,
                    'C' => [$metas[2]['value'] , $metas[2]['key']] ,
                    'D' => [$metas[3]['value'] , $metas[3]['key']] ,
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
        foreach ($input as $key => $value)
        {
            if (str_contains($key, 'answer-'))
            {
                $id = explode('-', $key);
                $data[$count]['id'] = $id[1];

                $answer = decrypt($value);
                if ($answer == 'option_true')
                {
                    $data[$count]['status'] = 2;
                    $data[$count]['select'] = $answer;
                    $true_answer++;
                }
                else
                {
                    $data[$count]['status'] = 1;
                    $data[$count]['select'] = $answer;
                }
                $count++;
            }
        }

        if (Auth::check())
        {
            $store['code_melli'] = Auth::user()->code_melli;
        }
        else
        {
            $store = Session::pull('volunteer_first_step');
        }

        $store['exam_passed_at'] = Carbon::now()->toDateTimeString();
        $store['exam_sheet'] = json_encode($data);
        $store['exam_result'] = ceil(($true_answer * 100) / $exam_question_count);
        
        if ($store['exam_result'] >= 50)
        {
            $msg = trans('site.global.volunteer_exam_passed_ok') . AppServiceProvider::pd($store['exam_result']) . trans('site.global.volunteer_exam_passed_ok1');
            $volunteer_status = 2;
            $ajax_status = 1;
        }
        else
        {
            $msg = trans('site.global.volunteer_exam_passed_nok');
            $volunteer_status = 1;
            $ajax_status = 0;
        }

        $user = User::selectBySlug($store['code_melli'], 'code_melli');
        if (! $user)
        {
            $store['volunteer_status'] = $volunteer_status;
            $id = User::store($store);
            if ($id)
            {
                Session::put('volunteer_exam_passed', $id);
                if ($store['exam_result'] >= 50)
                {
                    $return = $this->jsonFeedback(null, [
                        'ok' => $ajax_status,
                        'message' => $msg . trans('site.global.volunteer_exam_passed_ok2'),
                        'redirect' => url('/volunteers/final_step'),
                        'redirectTime' => 2000,
                    ]);
                }
                else
                {
                    $return = $this->jsonFeedback(null, [
                        'ok' => $ajax_status,
                        'message' => $msg,
                    ]);
                }
            }
            else
            {
                $return = $this->jsonFeedback(null, [
                    'ok' => 0,
                    'message' => trans('forms.feed.error'),
                ]);
            }
        }
        else
        {
            if ($user->isActive('volunteer'))
            {
                $update = [
                    'exam_passed_at' => $store['exam_passed_at'],
                    'exam_sheet' => $store['exam_sheet'],
                    'exam_result' => $store['exam_result'],
                    'id' => $user->id,
                ];
                $id = User::store($update);
                if ($id)
                {
                    if ($store['exam_result'] >= 50)
                    {
                        $return = $this->jsonFeedback(null, [
                            'ok' => $ajax_status,
                            'message' => $msg,
                            'redirect' => url('/manage'),
                            'redirectTime' => 2000,
                        ]);
                    }
                    else
                    {
                        $return = $this->jsonFeedback(null, [
                            'ok' => $ajax_status,
                            'message' => $msg,
                        ]);
                    }
                }
                else
                {
                    $return = $this->jsonFeedback(null, [
                        'ok' => 0,
                        'message' => trans('forms.feed.error'),
                    ]);
                }
            }
            elseif ($user->volunteer_status == 3)
            {
                $update = [
                    'exam_passed_at' => $store['exam_passed_at'],
                    'exam_sheet' => $store['exam_sheet'],
                    'exam_result' => $store['exam_result'],
                    'id' => $user->id,
                ];
                $id = User::store($update);
                if ($id)
                {
                    if ($store['exam_result'] >= 50)
                    {
                        $return = $this->jsonFeedback(null, [
                            'ok' => $ajax_status,
                            'message' => $msg . trans('site.global.volunteer_exam_passed_ok_volunteer_status_3'),
                        ]);
                    }
                    else
                    {
                        $return = $this->jsonFeedback(null, [
                            'ok' => $ajax_status,
                            'message' => $msg,
                        ]);
                    }
                }
                else
                {
                    $return = $this->jsonFeedback(null, [
                        'ok' => 0,
                        'message' => trans('forms.feed.error'),
                    ]);
                }
            }
            elseif ($user->volunteer_status == 1 or ($user->volunteer_status == 0 and $user->isActive('card')))
            {
                $update = [
                    'exam_passed_at' => $store['exam_passed_at'],
                    'exam_sheet' => $store['exam_sheet'],
                    'exam_result' => $store['exam_result'],
                    'volunteer_status' => $volunteer_status,
                    'id' => $user->id,
                ];

                $id = User::store($update);
                Session::put('volunteer_exam_passed', $id);
                if ($id)
                {
                    if ($store['exam_result'] >= 50)
                    {
                        $return = $this->jsonFeedback(null, [
                            'ok' => $ajax_status,
                            'message' => $msg . trans('site.global.volunteer_exam_passed_ok2'),
                            'redirect' => url('/volunteers/final_step'),
                            'redirectTime' => 2000,
                        ]);
                    }
                    else
                    {
                        $return = $this->jsonFeedback(null, [
                            'ok' => $ajax_status,
                            'message' => $msg,
                        ]);
                    }
                }
                else
                {
                    $return = $this->jsonFeedback(null, [
                        'ok' => 0,
                        'message' => trans('forms.feed.error'),
                    ]);
                }
            }
            else
            {
                $return = $this->jsonFeedback(null, [
                    'ok' => 0,
                    'message' => trans('forms.feed.error'),
                ]);
            }
        }

        return $return;
    }

    public function register_final_step()
    {
        $id = Session::get('volunteer_exam_passed');
        if (!$id)
            return redirect(url(''));
        $user = User::find($id);
        if (!$user or $user->volunteer_status != 2)
            return redirect(url(''));
        $volunteer = Post::findBySlug('volunteers_detail');
        if (! $volunteer)
            return redirect(url(''));
        $states = State::get_combo() ;
        $activity = Activity::all();

        return view('site.volunteers.volunteer_register.0', compact('user', 'volunteer', 'states', 'activity'));
        
    }

    public function register_final_step_submit(Requests\site\volunteer\VolunteerThirdStepRequest $request)
    {
        $input = $request->toArray();

        $input['id'] = Session::get('volunteer_exam_passed');
        $input['volunteer_status'] = 3;
        $input['home_province'] = State::find($input['home_city']);
        $input['work_province'] = State::find($input['work_city']);
        $input['domain'] = $input['home_province']->domain->slug ;

        $activity = '';
        foreach ($input['activity'] as $item => $value)
        {
            if ($item == count($input['activity']) - 2)
            {
                $activity .= $value;
            }
            else
            {
                $activity .= $value . ',';
            }
        }
        $input['activities'] = $activity;

        $update = User::store($input, ['activity']);
        
        if ($update)
        {
            $return = $this->jsonFeedback(null, [
                'ok' => 1,
                'message' => trans('site.global.volunteer_register_success'),
                'callback' => 'volunteer_final_step_form_data()',
            ]);
        }
        else
        {
            $return = $this->jsonFeedback(null, [
                'ok' => 0,
                'message' => trans('forms.feed.un_save'),
            ]);
        }

        Session::forget('volunteer_exam_passed');
        return $return;
    }
}
