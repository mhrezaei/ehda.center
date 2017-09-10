<?php

namespace App\Http\Requests\Front;

use Illuminate\Foundation\Http\FormRequest;

class TrackPurchasementRequest extends FormRequest
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
        return [
            'tracking_number' => 'required|exists:orders,tracking_number'
        ];
    }
}
