<?php

namespace App\Http\Requests\Manage;

use App\Http\Requests\Request;
use App\Models\Setting;
use App\Providers\ValidationServiceProvider;


class DownstreamSaveRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $input = $this->all();
        if($input['_submit'] == 'save')  {
            return [
                 'title' => 'required|unique:settings,title,'.$input['id'] ,
                 'slug' => 'required|alpha_dash|not_in:'.Setting::$reserved_slugs.'|unique:settings,slug,'.$input['id'],
                 'category' => 'required',
                 'data_type' => 'required',
            ];
        }
        else {
            return [] ;
        }

    }

    public function all()
    {
        $value	= parent::all();
        $purified = ValidationServiceProvider::purifier($value,[
        ]);
        return $purified;

    }

}
