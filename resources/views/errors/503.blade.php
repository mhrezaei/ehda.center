@extends('errors.full_template')
@section('error_code')
	{{ $error = 503 }}
@endsection
@section('message')
	{{ trans("validation.http.Error".$error) }}
@endsection