@extends('front.dialog.main')

{{ null, $dialogImage = true }}

@section('dialog-image-link')
    {{ url('assets/images/loading/bar-red.gif') }}
@endsection

@section('dialog-text')
    {{ trans('forms.feed.wait') }}
@endsection