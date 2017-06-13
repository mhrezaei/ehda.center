@extends('auth.form_frame', ['showLogo' => true])

@section('head')
    @parent
    <title>
        {{ setting()->ask('site_title')->gain() }} | {{ trans('front.register') }}
    </title>
    @include('front.frame.open_graph_meta_tags', ['description' => trans('front.register')])
@endsection

@section('formBox')

    {!! Form::open([
        'url'	=> '/register/new' ,
        'method'=> 'post',
        'class' => 'form-horizontal js',
        'name' => 'registerForm',
        'id' => 'registerForm',
    ]) !!}

    <div class="row">
        @include('front.forms.input',[
            'name' => 'name_first',
            'label' => false,
            'placeholder' => trans('validation.attributes.name_first'),
            'value' => old('name_first'),
            'containerClass' => 'field',
            'class' => 'input-lg form-persian form-required',
            'icon' => 'user',
            'extra' => 'autofocus',
        ])

        @include('front.forms.input',[
            'name' => 'name_last',
            'label' => false,
            'placeholder' => trans('validation.attributes.name_last'),
            'value' => old('name_last'),
            'containerClass' => 'field',
            'class' => 'input-lg form-persian form-required',
            'icon' => 'user',
        ])

        @include('front.forms.input',[
            'name' => 'code_melli',
            'label' => false,
            'placeholder' => trans('validation.attributes.code_melli'),
            'value' => old('code_melli'),
            'containerClass' => 'field',
            'class' => 'input-lg form-national form-required',
            'icon' => 'id-card',
        ])

        @include('front.forms.input',[
            'name' => 'mobile',
            'label' => false,
            'placeholder' => trans('validation.attributes.mobile'),
            'value' => old('mobile'),
            'containerClass' => 'field',
            'class' => 'input-lg form-mobile form-required',
            'icon' => 'phone',
        ])

        @include('front.forms.input',[
            'name' => 'email',
            'label' => false,
            'placeholder' => trans('validation.attributes.email'),
            'value' => old('email'),
            'containerClass' => 'field',
            'class' => 'input-lg form-email',
            'icon' => 'envelope',
        ])

        @include('front.forms.input',[
            'name' => 'password',
            'label' => false,
            'type' => 'password',
            'placeholder' => trans('validation.attributes.password'),
            'value' => old('password'),
            'containerClass' => 'field',
            'class' => 'input-lg form-password form-required',
            'icon' => 'key',
        ])

        @include('front.forms.input',[
            'name' => 'password2',
            'label' => false,
            'type' => 'password',
            'placeholder' => trans('validation.attributes.password2'),
            'value' => old('password2'),
            'containerClass' => 'field',
            'class' => 'input-lg form-required',
            'icon' => 'key',
        ])
    </div>

    <div class="row text-center">
        <div class="col-xs-12 mb10">
            <div class="row">
                <button class="btn btn-lg btn-block btn-green"> {{ trans('front.register') }} </button>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="row">
                @include('forms.feed')
            </div>
        </div>
        <div class="col-xs-12 mb10">
            <div class="row">
                <button type="button" onclick="window.location = '{{ route('login') }}';"
                        class="btn btn-block btn-blue"> {{ trans('front.member_login') }} </button>
            </div>
        </div>
    </div>
    <div class="row mt15">
        @if($errors->all())
            <div class="alert alert-danger" style="margin-top: 10px;">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif
    </div>
    {!! Form::close() !!}
@endsection

@section('endOfBody')
    {!! Html::script ('assets/libs/jquery.form.js') !!}
    {!! Html::script ('assets/js/forms.js') !!}
    <script>
        $(document).ready(function () {
            $('.log-btn').click(function () {
                $('.log-status').addClass('wrong-entry');
                $('.alert').fadeIn(500);
                setTimeout("$('.alert').fadeOut(1500);", 3000);
            });
            $('.form-control').keypress(function () {
                $('.log-status').removeClass('wrong-entry');
            });

        });
    </script>
@endsection

{{--@section('head')--}}
{{--@include('front.frame.open_graph_meta_tags', ['description' => trans('front.register')])--}}
{{--@endsection--}}
{{--@include('front.frame.header')--}}
{{--<title>--}}
{{--{{ setting()->ask('site_title')->gain() }} | {{ trans('front.register') }}--}}
{{--</title>--}}
{{--<body class="auth">--}}
{{--<div class="auth-wrapper">--}}
{{--<div class="auth-col">--}}
{{--<a href="{{ url_locale('') }}" class="logo"> <img src="{{ url('/assets/images/logo.png') }}"> </a>--}}
{{--</div>--}}
{{--<div class="auth-col">--}}
{{--<h1 class="auth-title"> {{ trans('front.register') }} </h1>--}}
{{--{!! Form::open([--}}
{{--'url'	=> '/register/new' ,--}}
{{--'method'=> 'post',--}}
{{--'class' => 'form-horizontal js',--}}
{{--'name' => 'registerForm',--}}
{{--'id' => 'registerForm',--}}
{{--]) !!}--}}

{{--@include('auth.field', [--}}
{{--'name' => 'name_first',--}}
{{--'other' => 'autofocus',--}}
{{--'type' => 'text',--}}
{{--'value' => old('name_first'),--}}
{{--'class' => 'form-required form-persian',--}}
{{--'min' => 2,--}}
{{--])--}}

{{--@include('auth.field', [--}}
{{--'name' => 'name_last',--}}
{{--'type' => 'text',--}}
{{--'value' => old('name_last'),--}}
{{--'class' => 'form-required form-persian',--}}
{{--'min' => 2,--}}
{{--])--}}

{{--@include('auth.field', [--}}
{{--'name' => 'code_melli',--}}
{{--'type' => 'text',--}}
{{--'value' => old('code_melli'),--}}
{{--'class' => 'form-required form-national',--}}
{{--])--}}

{{--@include('auth.field', [--}}
{{--'name' => 'mobile',--}}
{{--'type' => 'text',--}}
{{--'value' => old('mobile'),--}}
{{--'class' => 'form-required form-mobile',--}}
{{--])--}}

{{--@include('auth.field', [--}}
{{--'name' => 'email',--}}
{{--'type' => 'text',--}}
{{--'value' => old('email'),--}}
{{--'class' => 'form-email',--}}
{{--])--}}

{{--@include('auth.field', [--}}
{{--'name' => 'password',--}}
{{--'type' => 'password',--}}
{{--'value' => old('password'),--}}
{{--'class' => 'form-required form-password',--}}
{{--])--}}

{{--@include('auth.field', [--}}
{{--'name' => 'password2',--}}
{{--'type' => 'password',--}}
{{--'value' => old('password2'),--}}
{{--'class' => 'form-required',--}}

{{--])--}}

{{--<div class="tal" style="margin-bottom: 15px;">--}}
{{--<button class="green block" type="submit"> {{ trans('front.register') }} </button>--}}
{{--</div>--}}
{{--@include('forms.feed')--}}
{{--<hr class="or">--}}
{{--<div class="tal" style="margin-bottom: 15px;">--}}
{{--<button onclick="window.location = '{{ url('/login') }}';"--}}
{{--class="blue block"> {{ trans('front.member_login') }} </button>--}}
{{--</div>--}}

{{--{!! Form::close() !!}--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}
{{--@include('front.frame.scripts')--}}
{{--</body>--}}

{{--</html>--}}