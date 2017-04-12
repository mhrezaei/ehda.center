<?php

namespace App\Http\Requests\Manage;

use Illuminate\Foundation\Http\FormRequest;
use App\Providers\ValidationServiceProvider ;

class ReceiptSaveRequest extends FormRequest
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
        return [
             'id' => 'numeric' ,
             'user_id' => 'numeric' ,
             'code' => 'numeric|unique:receipts,code' ,
        ];
    }

    public function all()
    {
        $value	= parent::all();
        $purified = ValidationServiceProvider::purifier($value,[
             'id'  =>  'ed|numeric',
             'code' => "ed|stripMask",
        ]);
        return $purified;

    }

}
