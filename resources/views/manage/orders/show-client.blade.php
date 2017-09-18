{{--
|--------------------------------------------------------------------------
| Single Row of Client Name
|--------------------------------------------------------------------------
| This is to be called wherever a single-line client name
| (including all the links and hints) is required: browse-row
--}}
@php $row = isset($order) ? $order : $model @endphp

@if($row->id)

    @include("manage.frame.widgets.grid-text" , [
        'icon' => $row->is_by_admin ? 'user-circle-o' : 'user',
        'text' => $row->client_name ,
        'link' => $row->user? "urlN:manage/users/browse/all/search?id=".$row->user_id."&searched=1" : null,
    ]     )

@else

    @include("manage.frame.widgets.grid-text" , [
        'icon' => "user-o" ,
        'text' => "$row->name ($row->email): " ,
    ])

@endif

