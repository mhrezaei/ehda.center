<?php

namespace App\Http\Requests\Front;

use App\Models\Comment;
use App\Models\Post;
use App\Providers\CommentServiceProvider;
use App\Providers\ValidationServiceProvider;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class DropzoneUploadRequest extends FormRequest
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
            '_uploadIdentifier' => 'required',
        ];
    }

    public function all()
    {
        $value = parent::all();
        $file = $value['file'];

        $purified = ValidationServiceProvider::purifier($value, [
            '_uploadIdentifier' => 'decrypt',
        ]);

        $purified['file'] = $file;

        return $purified;
    }

}
