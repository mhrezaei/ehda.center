@extends('errors.mini_template')
@section('error_code')
	{{ $error = 410 }}
@endsection
@section('message')
	{{ trans("validation.http.Error".$error) }}
@endsection