<?php

namespace App\Http\Requests\Manage;

use App\Http\Requests\Request;
use App\Models\Role;
use App\Providers\ValidationServiceProvider;


class RoleSaveRequest extends Request
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
	    $id = $input['id'];
        if($input['_submit'] == 'save') {
            return [
                 'slug' => 'required|alpha_dash|not_in:'.Role::$reserved_slugs.'|unique:roles,slug,'.$id.',id',
                 'title' => 'required|unique:roles,title,'.$id.',id',
                 'plural_title' => 'required',
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
            'slug' => "lower",
        ]);
        return $purified;

    }

}
