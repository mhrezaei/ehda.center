<?php

namespace App\Http\Requests\FileManager;

class SetFileDetailsRequest extends FileManagerRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'fileKey' => 'required',
        ];
    }
}
