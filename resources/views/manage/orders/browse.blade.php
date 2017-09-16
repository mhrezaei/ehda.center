@extends('manage.frame.use.0')

@section('section')
    <div id="divTab">
        @include('manage.orders.tabs')
    </div>

    {{--@include("manage.frame.widgets.toolbar" , [--}}
    {{--'subtitle_view' => "manage.comments.browse-subtitle" ,--}}
    {{--'mass_actions' => [--}}
    {{--['legal' , trans('forms.button.change_status') , 'modal:manage/comments/act/-id-/status' , $page[1][0]!='bin'],--}}
    {{--['trash-o' , trans('forms.button.soft_delete') , "modal:manage/comments/act/-id-/delete", $page[1][0]!='bin'],--}}
    {{--['recycle' , trans('forms.button.undelete') , "modal:manage/comments/act/-id-/undelete" , $page[1][0]=='bin'],--}}
    {{--['times' , trans('forms.button.hard_delete') , "modal:manage/comments/act/-id-/destroy" , $page[1][0]=='bin'],--}}
    {{--] ,--}}
    {{--'buttons-' => [--}}
    {{--[--}}
    {{--'target' => url("manage/posts/$posttype->slug/create/locale"),--}}
    {{--'type' => "success",--}}
    {{--'caption' => trans('forms.button.add_to').' '.$posttype->title ,--}}
    {{--'icon' => "plus-circle",--}}
    {{--],--}}
    {{--],--}}
    {{--])--}}

    @include("manage.frame.widgets.grid" , [
        'table_id' => "tblOrders",
        'row_view' => "manage.orders.browse-row",
        'handle' => "selector",
        'headings' => [
            trans('validation.attributes.properties'),
            trans('validation.attributes.amount_invoiced') . ' (' . trans('front.toman').  ')',
            trans('validation.attributes.amount_payable') . ' (' . trans('front.toman').  ')',
            trans('validation.attributes.amount_paid') . ' (' . trans('front.toman').  ')',
            [trans('validation.attributes.status'),150],
            trans('forms.button.action')
        ],
    ])

@endsection