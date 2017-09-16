{{--
|--------------------------------------------------------------------------
| Single Row of Order's Posts
|--------------------------------------------------------------------------
| This is to be called wherever a single-line sender name
| (including all the links and hints) is required: browse-row
--}}
@php
    $row = isset($order) ? $order : $model;
    $posts = $row->posts;
@endphp

@foreach($posts as $key => $post)
    @if($key != 0) <br /> @endif
    @if($post)
        @include("manage.frame.widgets.grid-text" , [
            'text' => str_limit($post->posttype->title . ' / ' . $post->title , 100),
            'link' => "urlN:$post->browse_link" ,
            'icon' => $post->posttype->spreadMeta()->icon ,
        ])
    @else
        @include("manage.frame.widgets.grid-text" , [
            'text' => trans('posts.form.deleted_post') ,
        ])
    @endif
@endforeach