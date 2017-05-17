{{ null,
    $previousComments = PostsServiceProvider::getPostComments($post, ['user_id' => user()->id])
}}

{{ null, $updateUrl = url_locale('user/dashboard/previous_comments/' . $post->hash_id) }}

<div class="previous-comments" data-url="{{ $updateUrl }}">
    @if($previousComments)
        <table class="table">
            <tbody>
            @foreach($previousComments as $key => $comment)
                {{ null, $children = $comment->parent()->children()->get() }}
                {{ null, $childrenCount = $children->count() }}
                <tr>
                    <td>
                        @include("manage.frame.widgets.grid-text" , [
                            'text' => str_limit($comment->text, 100),
                            'text2' => $comment->text ,
                            'size' => "11" ,
                        ])
                        @include('manage.frame.widgets.grid-date', ['date' => $comment->created_at])
                    </td>
                    <td>
                        @include("manage.frame.widgets.grid-text" , [
                            'text' => trans("forms.status_text.$comment->status") ,
                        ])
                        @include("manage.frame.widgets.grid-tiny" , [
                            'icon' => $comment->replied_on? "comments-o" : "comment-o",
                            'text' => pd($childrenCount) . ' ' . trans('posts.comments.reply') ,
                        ])
                        @if($childrenCount)
                            <button class="icon-button" data-toggle="collapse" data-target="#collapse{{$key}}">
                                <i class="fa fa-chevron-down"></i>
                            </button>
                        @endif
                    </td>
                </tr>
                @if($childrenCount)
                    <tr>
                        <td colspan="5" id="collapse{{$key}}" class="collapse">
                            <div class="col-xs-10 col-center">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <td>{{ trans('posts.comments.replies') }}</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($children as $child)
                                        <tr>
                                            <td>
                                                @include("manage.frame.widgets.grid-text" , [
                                                    'text' => str_limit($child->text, 100),
                                                    'text2' => $child->text ,
                                                    'size' => "11" ,
                                                ])
                                                @include('manage.frame.widgets.grid-date', ['date' => $child->created_at])
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>
    @endif
</div>