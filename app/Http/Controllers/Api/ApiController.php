<?php

namespace App\Http\Controllers\Api;

use App\Models\Api_ips;
use App\Http\Controllers\Controller;
use App\Models\Api_token;
use App\Models\Post;
use App\Models\Printing;
use App\Models\State;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class ApiController extends Controller
{
    public function __construct()
    {
        Api_token::delete_expired();
    }

    public function index()
    {
        return redirect(url('/fa/show-post/api-documentation'));
    }

    public function get_token(Requests\Api\GetTokenRequest $request)
    {
        $data = $request->toArray();
        $data['ip'] = $request->ip();
        $result = array();
        $client = false;

        // check submit data
        if (! isset($data['username']) or ! isset($data['password']))
        {
            // username and password not send to api
            $result['status'] = 0;
        }
        else
        {
            // find client by username
            $client = User::findBySlug($data['username'], 'code_melli');

            if ($client and $client->hasRole('api'))
            {
                if (Hash::check($data['password'], $client['password']))
                {
                    // password is true
                    // check request ip address
                    if (self::validationIpAddress($client['id'], $data['ip']))
                    {
                        // request ip and client ip is match
                        $result['status'] = 1;
                    }
                    else
                    {
                        // request ip and client ip not match
                        $result['status'] = -3;
                    }
                }
                else
                {
                    // password wrong
                    $result['status'] = -1;
                }
            }
            else
            {
                // username wrong
                $result['status'] = -1;
            }


        }

        // get token process
        if ($result['status'] > 0)
        {
            $result['token'] = str_random(100);

            // check token for duplicate
            $check_db_token = Api_token::findBySlug($result['token'], 'api_token');
            if ($check_db_token)
            {
                $result['token'] = str_random(100);
            }

            $insert = [
                'user_id' => $client['id'],
                'api_token' => $result['token'],
                'expired_at' => Carbon::now()->addHour()->toDateTimeString(),
            ];

            if (Api_token::store($insert))
            {
                $result['token'] = encrypt($result['token']);
            }
            else
            {
                // token insert to database failed
                $result['status'] = -4;
            }

        }

        return json_encode($result);
    }

    public function ehda_card_search(Requests\Api\EhdaCardSearchRequest $request)
    {
        $data = $request->toArray();
        $result = array();

        if (! isset($data['token']) or ! isset($data['code_melli']))
            // code_melli or token not send
            $result['status'] = -5;

        // token check
        $token = self::validateToken($request->token, $request->ip());
        if (is_numeric($token) and $token <= 0)
        {
            // token not valid
            $result['status'] = $token;
        }
        else
        {
            // code_melli check
            if (self::validateCodeMelli($data['code_melli']))
            {
                $user = User::findBySlug($data['code_melli'], 'code_melli');

                if ($user)
                {
                    if ($user->is_an('card-holder'))
                    {
                        // user already exist and active
                        $result['status'] = 2;
                    }
                    else
                    {
                        // user exist but it have'nt ehda card
                        $result['status'] = -10;
                    }
                }
                else
                {
                    // user not found
                    $result['status'] = -10;
                }
            }
            else
            {
                // code_melli not valid
                $result['status'] = -8;
            }
        }

        return json_encode($result);
    }

    public function get_card(Requests\Api\GetEhdaCardRequest $request)
    {
        $data = $request->toArray();
        $result = array();

        $rules = [
            'token' => 'required',
            'code_melli' => 'required|code_melli',
            'birth_date' => 'required',
            'tel_mobile' => 'phone:mobile', // optional
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            // data validation failed
            $result['status'] = -11;
        }

        // token check
        $token = self::validateToken($request->token, $request->ip());
        if (is_numeric($token) and $token <= 0)
        {
            // token not valid
            $result['status'] = $token;
        }
        else
        {
            // code_melli check
            if (self::validateCodeMelli($data['code_melli']))
            {
                $user = User::findBySlug($data['code_melli'], 'code_melli');

                if ($user)
                {
                    if ($user->is_an('card-holder'))
                    {
                        if ($user->birth_date->toDateString() == Carbon::createFromTimestamp($data['birth_date'])->toDateString())
                        {
                            if (isset($data['tel_mobile']))
                            {
                                if ($user->mobile == $data['tel_mobile'])
                                {
                                    // validation success and card attach
                                    $result['status'] = 3;
                                    $result = array_merge($result, self::create_ehda_card_link($request->code_melli), self::create_ehda_card_detail($user));
                                }
                                else
                                {
                                    // tel_mobile not match with user data
                                    $result['status'] = -12;
                                }
                            }
                            else
                            {
                                // validation success and card attach
                                $result['status'] = 3;
                                $result = array_merge($result, self::create_ehda_card_link($request->code_melli), self::create_ehda_card_detail($user));
                            }
                        }
                        else
                        {
                            // birth_date not match with user data
                            $result['status'] = -12;
                        }
                    }
                    else
                    {
                        // user exist but it have'nt ehda card
                        $result['status'] = -9;
                    }
                }
                else
                {
                    // user not found
                    $result['status'] = -10;
                }
            }
            else
            {
                // code_melli not valid
                $result['status'] = -8;
            }
        }

        return json_encode($result);
    }

    // new card
    public function get_card_new(Requests\Api\GetEhdaCardRequest $request)
    {
        $data = $request->toArray();
        $result = array();

        $rules = [
            'token' => 'required',
            'code_melli' => 'required|code_melli',
            'birth_date' => 'required',
            'tel_mobile' => 'phone:mobile', // optional
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            // data validation failed
            $result['status'] = -11;
        }

        // token check
        $token = self::validateToken($request->token, $request->ip());
        if (is_numeric($token) and $token <= 0)
        {
            // token not valid
            $result['status'] = $token;
        }
        else
        {
            // code_melli check
            if (self::validateCodeMelli($data['code_melli']))
            {
                $user = User::findBySlug($data['code_melli'], 'code_melli');

                if ($user)
                {
                    if ($user->is_an('card-holder'))
                    {
                        if ($user->birth_date->toDateString() == Carbon::createFromTimestamp($data['birth_date'])->toDateString())
                        {
                            if (isset($data['tel_mobile']))
                            {
                                if ($user->mobile == $data['tel_mobile'])
                                {
                                    // validation success and card attach
                                    $result['status'] = 3;
                                    $result = array_merge($result, self::create_ehda_card_link_new($request->code_melli), self::create_ehda_card_detail($user));
                                }
                                else
                                {
                                    // tel_mobile not match with user data
                                    $result['status'] = -12;
                                }
                            }
                            else
                            {
                                // validation success and card attach
                                $result['status'] = 3;
                                $result = array_merge($result, self::create_ehda_card_link_new($request->code_melli), self::create_ehda_card_detail($user));
                            }
                        }
                        else
                        {
                            // birth_date not match with user data
                            $result['status'] = -12;
                        }
                    }
                    else
                    {
                        // user exist but it have'nt ehda card
                        $result['status'] = -9;
                    }
                }
                else
                {
                    // user not found
                    $result['status'] = -10;
                }
            }
            else
            {
                // code_melli not valid
                $result['status'] = -8;
            }
        }

        return json_encode($result);
    }

    public function ehda_card_register(Requests\Api\EhdaCardRegisterRequest $request)
    {
        $result = array();
        $user_id = 0;

        // token check
        $token = self::validateToken($request->token, $request->ip());
        if (is_numeric($token) and $token <= 0)
        {
            // token not valid
            $result['status'] = $token;
        }
        else
        {
            $rules = [
                'name_first' => 'required|persian:60',
                'name_last' => 'required|persian:60',
                'code_melli' => 'required|code_melli',
                'gender' => 'required|numeric|min:1|max:3',
                'name_father' => 'required|persian:60',
                'code_id' => 'numeric', // optional
                'birth_date' => 'required',
                'birth_city' => 'numeric|min:1', // optional
                'edu_level' => 'numeric|min:1|max:6', // optional
                'tel_mobile' => 'required|phone:mobile',
                'home_city' => 'required|numeric|min:1',
                'event' => 'string',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                // data validation failed
                $result['status'] = -15;
            }
            else
            {
                // validation success full
                $result['status'] = 1;
            }


            // event id check
            if ($request->event)
                $event_id = hashid($request->event);
            else
                $event_id = 0;
        }

        if ($result['status'] > 0)
        {
            // data
            $input = $request->toArray();

            //$input['card_status'] = 8;
            $input['password'] = Hash::make($input['tel_mobile']);
            $input['mobile'] = $input['tel_mobile'];
            $input['home_province'] = State::find($input['home_city']);
            $input['domain'] = $input['home_province']->domain->slug ; // @TODO: ask from taha
            $input['home_province'] = $input['home_province']->province()->id;
            $input['password_force_change'] = 1;
            $input['card_registered_at'] = Carbon::now()->toDateTimeString();
            $input['created_by'] = $token->user->id;
            $input['from_event_id'] = $event_id;

            // check birth date range
            $minimum = -1539449865;
            $maximum = Carbon::now()->timestamp;
            if ($input['birth_date'] <= $minimum or $input['birth_date'] > $maximum)
            {
                // data validation failed
                $result['status'] = -15;
            }
            else
            {
                $input['birth_date'] = Carbon::createFromTimestamp($input['birth_date'])->toDateString();
            }

            // unset additional data
            unset($input['token']);
            unset($input['tel_mobile']);

            $user = User::findBySlug($request->code_melli, 'code_melli');
            if (! $user)
            {
                $user_id = User::store($input);

                // card register failed if store not complete
                $result['status'] = -16;
            }
            else
            {
                if ($user->is_admin() or $user->is_an('card-holder'))
                {
                    // user already exist and active
                    $result['status'] = 2;
                }
                else
                {
                    $input['id'] = $user->id;
                    $user_id = User::store($input);

                    // card register failed
                    $result['status'] = -16;
                }
            }
        }

        // generate card_no
        if ($user_id and $user_id > 0)
        {
            $update['id'] = $user_id;
            $update['card_no'] = $user_id + 5000;
            $user_id = User::store($update);

            // add card-holder role
            $user = User::find($user_id);
            if ($user)
                $user->attachRole('card-holder');

            // card register success and ehda card attach
            $result['status'] = 3;
            $result = array_merge($result, self::create_ehda_card_link($request->code_melli), self::create_ehda_card_detail($user));
        }

        return json_encode($result);
    }

    // new card
    public function ehda_card_register_new(Requests\Api\EhdaCardRegisterRequest $request)
    {
        $result = array();
        $user_id = 0;

        // token check
        $token = self::validateToken($request->token, $request->ip());
        if (is_numeric($token) and $token <= 0)
        {
            // token not valid
            $result['status'] = $token;
        }
        else
        {
            $rules = [
                'name_first' => 'required|persian:60',
                'name_last' => 'required|persian:60',
                'code_melli' => 'required|code_melli',
                'gender' => 'required|numeric|min:1|max:3',
                'name_father' => 'required|persian:60',
                'code_id' => 'numeric', // optional
                'birth_date' => 'required',
                'birth_city' => 'numeric|min:1', // optional
                'edu_level' => 'numeric|min:1|max:6', // optional
                'tel_mobile' => 'required|phone:mobile',
                'home_city' => 'required|numeric|min:1',
                'event' => 'string',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                // data validation failed
                $result['status'] = -15;
            }
            else
            {
                // validation success full
                $result['status'] = 1;
            }

            // event id check
            if ($request->event)
                $event_id = hashid($request->event);
            else
                $event_id = 0;
        }

        if ($result['status'] > 0)
        {
            // data
            $input = $request->toArray();

            //$input['card_status'] = 8;
            $input['password'] = Hash::make($input['tel_mobile']);
            $input['mobile'] = $input['tel_mobile'];
            $input['home_province'] = State::find($input['home_city']);
            $input['domain'] = $input['home_province']->domain->slug ; // @TODO: ask from taha
            $input['home_province'] = $input['home_province']->province()->id;
            $input['password_force_change'] = 1;
            $input['card_registered_at'] = Carbon::now()->toDateTimeString();
            $input['created_by'] = $token->user->id;
            $input['from_event_id'] = $event_id;

            // check birth date range
            $minimum = -1539449865;
            $maximum = Carbon::now()->timestamp;
            if ($input['birth_date'] <= $minimum or $input['birth_date'] > $maximum)
            {
                // data validation failed
                $result['status'] = -15;
            }
            else
            {
                $input['birth_date'] = Carbon::createFromTimestamp($input['birth_date'])->toDateString();
            }

            // unset additional data
            unset($input['token']);
            unset($input['tel_mobile']);

            $user = User::findBySlug($request->code_melli, 'code_melli');
            if (! $user)
            {
                $user_id = User::store($input);

                // card register failed if store not complete
                $result['status'] = -16;
            }
            else
            {
                if ($user->is_admin() or $user->is_an('card-holder'))
                {
                    // user already exist and active
                    $result['status'] = 2;
                }
                else
                {
                    $input['id'] = $user->id;
                    $user_id = User::store($input);

                    // card register failed
                    $result['status'] = -16;
                }
            }
        }

        // generate card_no
        if ($user_id and $user_id > 0)
        {
            $update['id'] = $user_id;
            $update['card_no'] = $user_id + 5000;
            $user_id = User::store($update);

            // add card-holder role
            $user = User::find($user_id);
            if ($user)
                $user->attachRole('card-holder');

            // card register success and ehda card attach
            $result['status'] = 3;
            $result = array_merge($result, self::create_ehda_card_link_new($request->code_melli), self::create_ehda_card_detail($user));
        }

        return json_encode($result);
    }


    // save print request
    public function save_print_request(Request $request)
    {
        $data = $request->toArray();
        $result = array();

        $rules = [
            'token' => 'required',
            'code_melli' => 'required|code_melli',
            'event' => 'string',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
        {
            // data validation failed
            $result['status'] = -20;
        }

        // check event id
        if ($request->event)
            $event_id = hashid($request->event);
        else
            $event_id = 0;


        // token check
        $token = self::validateToken($request->token, $request->ip());
        if (is_numeric($token) and $token <= 0)
        {
            // token not valid
            $result['status'] = $token;
        }
        else
        {
            $user = User::findBySlug($request->code_melli, 'code_melli');
            if ($user and $user->id)
            {
                if (!$user->is_an('card-holder'))
                {
                    // user don't have a card
                    $result['status'] = -21;
                }
                else
                {
                    $now = Carbon::now()->toDateTimeString();
                    $insert = array(
                        'user_id' => $user->id,
                        'event_id' => $event_id,
                        'queued_at' => $now,
                        'queued_by' => $token->user_id,
                        'printed_at' => $now,
                        'printed_by' => $token->user_id,
                        'verified_at' => $now,
                        'verified_by' => $token->user_id,
                        'dispatched_at' => $now,
                        'dispatched_by' => $token->user_id,
                        'delivered_at' => $now,
                        'delivered_by' => $token->user_id,
                        'created_by' => $token->user_id,
                        'updated_by' => $token->user_id,
                    );
                    Printing::store($insert);

                    // user print request inserted
                    $result['status'] = 9;
                }
            }
            else
            {
                // user not found
                $result['status'] = -21;
            }
        }

        return json_encode($result);
    }

    public function get_province(Requests\Api\GetProvinceListRequest $request)
    {
        $data = $request->toArray();
        $result = array();

        if (! isset($data['token']))
            // token not send
            $result['status'] = -13;

        // token check
        $token = self::validateToken($request->token, $request->ip());
        if (is_numeric($token) and $token <= 0)
        {
            // token not valid
            $result['status'] = $token;
        }
        else
        {
            // province successfully listed
            $result['status'] = 4;
            $province = State::getProvinces()->select('id', 'title')->get();
            $result['province'] = $province;
        }

        return json_encode($result);
    }

    public function get_cities(Requests\Api\GetCitiesListRequest $request)
    {
        $data = $request->toArray();
        $result = array();

        if (! isset($data['token']) and ! isset($data['province']))
            // token or province id not send
            $result['status'] = -14;

        // token check
        $token = self::validateToken($request->token, $request->ip());
        if (is_numeric($token) and $token <= 0)
        {
            // token not valid
            $result['status'] = $token;
        }
        else
        {
            // cities successfully listed
            $result['status'] = 5;
            $cities = State::getCities($request->province)->select('id', 'title')->get();
            $result['cities'] = $cities;
        }

        return json_encode($result);
    }

    public function get_education(Requests\Api\GetEducationListRequest $request)
    {
        $data = $request->toArray();
        $result = array();

        if (! isset($data['token']))
            // token not send
            $result['status'] = -13;

        // token check
        $token = self::validateToken($request->token, $request->ip());
        if (is_numeric($token) and $token <= 0)
        {
            // token not valid
            $result['status'] = $token;
        }
        else
        {
            // education successfully listed
            $result['status'] = 6;
            $education = trans('people.edu_level_full');
            if (is_array($education))
            {
                foreach ($education as $key => $value)
                {
                    $result['education'][] = ['id' => $key, 'title' => $value];
                }
            }
            else
            {
                // education not listed
                $result['status'] = -19;
            }
        }

        return json_encode($result);
    }

    public function get_prepare_config(Requests\Api\PrepareConfigRequest $request)
    {
        $data = $request->toArray();
        $result = array();

        if (! isset($data['token']))
            // token not send
            $result['status'] = -5;

        // token check
        $token = self::validateToken($request->token, $request->ip());
        if (is_numeric($token) and $token <= 0)
        {
            // token not valid
            $result['status'] = $token;
        }
        else
        {
            // prepare portable printer config
            $config = setting('prepare_portable_printer_config')->gain();
            if ($config)
            {
                // prepare config success return
                $result['status'] = 7;
                $result['config'] = json_decode($config, true);
            }
            else
            {
                // prepare config can't be return
                $result['status'] = -17;
            }
        }

        return json_encode($result);
    }

    public function get_printers_slideshow(Requests\Api\SlideShowRequest $request)
    {
        $data = $request->toArray();
        $result = array();

        if (! isset($data['token']))
            // token not send
            $result['status'] = -5;

        // token check
        $token = self::validateToken($request->token, $request->ip());
        if (is_numeric($token) and $token <= 0)
        {
            // token not valid
            $result['status'] = $token;
        }
        else
        {
            // load printers slideshows
            $slideshows = Post::selector([
                'category' => 'printers-slideshow',
                'type' => 'slideshows',
            ])->get();
//            dd($slideshows->toArray());
            if ($slideshows)
            {
                // generate content to return
                $result['status'] = 8;
                $result['slideshow'] = array();

                $i = 0;
                foreach ($slideshows as $slideshow)
                {
                    $result['slideshow'][$i]['picture'] = url($slideshow->featured_image);
                    $result['slideshow'][$i]['title'] = $slideshow->title;
                    $i++;
                }
            }
            else
            {
                // content for return not fund
                $result['status'] = -18;
            }
        }

        return json_encode($result);
    }

    // get active events list
    public function get_active_events_list(Requests\Api\GetProvinceListRequest $request)
    {
        $data = $request->toArray();
        $result = array();

        if (! isset($data['token']))
            // token not send
            $result['status'] = -13;

        // token check
        $token = self::validateToken($request->token, $request->ip());
        if (is_numeric($token) and $token <= 0)
        {
            // token not valid
            $result['status'] = $token;
        }
        else
        {
            $events = Post::selector([
                'type' => 'event'
            ])
                ->where('starts_at', '<=', Carbon::now()->toDateTimeString())
                ->where('ends_at', '>=', Carbon::now()->toDateTimeString())
                ->orderByDesc('published_at')
                ->get();

            if ($events and $events->count())
            {
                // events successfully listed
                $result['status'] = 9;
                foreach ($events as $event)
                {
                    $me = [
                        'title' => $event->title,
                        'unique_id' => hashid($event->id)
                    ];
                    $result['events_list'][] = $me;
                }
            }
            else
            {
                // events not found
                $result['status'] = -22;
            }
        }

        return json_encode($result);
    }

    // code_melli_validation
    private static function validateCodeMelli($code_melli)
    {
        if(!preg_match("/^\d{10}$/", $code_melli)) {
            return false;
        }

        $check = (int)$code_melli[9];
        $sum = array_sum(array_map(function ($x) use ($code_melli) {
                return ((int)$code_melli[$x]) * (10 - $x);
            }, range(0, 8))) % 11;

        return ($sum < 2 && $check == $sum) || ($sum >= 2 && $check + $sum == 11);
    }

    // token validation
    private static function validateToken($request_token, $request_ip)
    {
        // token check
        $token = Api_token::find_token($request_token);
        if ($token)
        {
            if ($token->expired_at <= Carbon::now()->toDateTimeString())
            {
                // token expired
                return -7;
            }
            else
            {
                if (self::validationIpAddress($token->user->id, $request_ip))
                {
                    // token is valid
                    return $token;
                }
                else
                {
                    // request ip and client ip not match
                    return -3;
                }
            }
        }
        else
        {
            // wrong token
            return -6;
        }
    }

    // user ip address validation
    private static function validationIpAddress($user_id, $request_ip)
    {
        $user = User::find($user_id);

        if ($user->as('api')->status() == 1)
        {
            // user ip validation request ip should equal to stored ip for user
            $ip = Api_ips::where('user_id', $user_id)
                ->where('slug', $request_ip)
                ->first();
        }
        elseif ($user->as('api')->status() == 2)
        {
            // user don't need validation with ip address
            $ip = true;
        }
        else
        {
            // user have not permission
            $ip = false;
        }

         if ($ip)
         {
             return $ip;
         }
         else
         {
             return false;
         }
    }

    // create ehda card links
    private static function create_ehda_card_link($code_melli)
    {
        $cards = array();
        $user = User::findBySlug($code_melli, 'code_melli');
        if (!$user)
            return $cards;

        $cards['ehda_card_mini'] = $user->cards('mini');
        $cards['ehda_card_single'] = $user->cards('single');
        $cards['ehda_card_social'] = $user->cards('social');
        $cards['ehda_card_print'] = $user->cards('full', 'print');
        $cards['ehda_card_download'] = $user->cards('mini', 'download');
        return $cards;
    }

    // create new ehda card link
    private static function create_ehda_card_link_new($code_melli)
    {
        $cards = array();
        $user = User::findBySlug($code_melli, 'code_melli');
        if (!$user)
            return $cards;

        $cards['ehda_card_mini'] = $user->newCards('mini');
        $cards['ehda_card_single'] = $user->newCards('single');
        $cards['ehda_card_social'] = $user->newCards('social');
        $cards['ehda_card_print'] = $user->newCards('full', 'print');
        $cards['ehda_card_download'] = $user->newCards('mini', 'download');
        return $cards;
    }

    // create ehda card details for remote print
    private static function create_ehda_card_detail($user)
    {
        $cards_detail = array();
        if (is_object($user)) {
            $user = $user;
        }

        if (is_numeric($user)) {
            $user = find($user);
        }

        if ($user)
        {
            $cards_detail['ehda_card_details']['register_no'] = $user->card_no;
            $cards_detail['ehda_card_details']['full_name'] = $user->name_first . ' ' . $user->name_last;
            $cards_detail['ehda_card_details']['father_name'] = $user->name_father;
            $cards_detail['ehda_card_details']['code_melli'] = $user->code_melli;
            $cards_detail['ehda_card_details']['birth_date'] = echoDate($user->birth_date, 'Y/m/d', 'fa', false);
            $cards_detail['ehda_card_details']['registered_at'] = echoDate($user->card_registered_at, 'Y/m/d', 'fa', false);
            $cards_detail['ehda_card_details']['full_data'] = $user->name_first . ' ' . $user->name_last . ' - ' . $user->card_no;
            $cards_detail['ehda_card_details']['unique_id'] = hashid($user->id);

            return $cards_detail;
        }
        else
        {
            return [];
        }
    }
}
