<?php

namespace App\Http\Controllers\Api;

use App\Api_ips;
use App\Http\Controllers\Controller;
use App\Models\Api_token;
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

        if (! isset($data['token'])
            or ! isset($data['code_melli'])
            or ! isset($data['birth_date'])
            or ! isset($data['tel_mobile'])
        )
            // code_melli or token or birth_date or tel_mobile not send
            $result['status'] = -11;

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
                        if ($user->tel_mobile == $request->tel_mobile
                            and $user->birth_date == Carbon::createFromTimestamp($request->birth_date)->toDateString()
                        )
                        {
                            // validation success and card attach
                            $result['status'] = 3;
                            $result = array_merge($result, self::create_ehda_card_link($request->code_melli));
                        }
                        else
                        {
                            // birth_date and tel_mobile not match with user data
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
        }

        if ($result['status'] > 0)
        {
            // data
            $input = $request->toArray();
            unset($input['token']);
            //$input['card_status'] = 8;
            $input['password'] = Hash::make($input['tel_mobile']);
            $input['home_province'] = State::find($input['home_city']);
            $input['domain'] = $input['home_province']->domain->slug ; // @TODO: ask from taha
            $input['home_province'] = $input['home_province']->province()->id;
            $input['password_force_change'] = 1;
            $input['card_registered_at'] = Carbon::now()->toDateTimeString();
            $input['created_by'] = $token->user->id;

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


            $user = User::findBySlug($request->code_melli, 'code_melli');
            if (! $user)
            {
                $user_id = User::store($input);

                // card register failed
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
            $result = array_merge($result, self::create_ehda_card_link($request->code_melli));
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
            $education = trans('people.education');
            foreach ($education as $key => $value)
            {
                $result['education'][] = ['id' => $key, 'title' => $value];
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
                if (self::validationIpAddress($token->user->id, $request_ip) and $token->user->hasRole('api'))
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
         $ip = Api_ips::where('user_id', $user_id)
             ->where('slug', $request_ip)
             ->first();

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
}
