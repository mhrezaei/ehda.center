<?php

namespace App\Http\Requests\Front;

use App\Models\Post;
use App\Providers\ValidationServiceProvider;
use Illuminate\Foundation\Http\FormRequest;

class PurchaseProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $input = parent::all();
        if (!isset($input['post_id']) or !$input['post_id']) {
            return false;
        }

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
        $post = Post::find($input['post_id']);

        return [
            'post_id' => 'exists:posts,id',
            'name'    => 'persian:60',
            'email'   => 'required|email',
            'mobile'  => 'phone:mobile',
            'price'   => 'required|numeric|min:' . ($post->exists ? $post->price : 0)
        ];
    }

    public function all()
    {
        $input = parent::all();

        $purified = ValidationServiceProvider::purifier($input, [
            'post_id'    => 'dehash',
            'name'       => 'pd',
            'mobile'     => 'ed',
        ]);

        return $purified;

    }
}
