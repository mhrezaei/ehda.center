@extends('auth.form_frame', ['showLogo' => true])

@section('head')
    @parent
    <title>
        {{ setting()->ask('site_title')->gain() }} | {{ trans('front.login') }}
    </title>
@endsection
@include('front.frame.open_graph_meta_tags', ['description' => trans('front.login')])

@section('formBox')
    <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
        {{ csrf_field() }}
        <div class="form-group">
            <input type="text" name="code_melli" id="code_melli" class="form-control input-lg"
                   placeholder="{{ trans('validation.attributes.code_melli') }}" autofocus autocomplete="off">
            <i class="fa fa-user"></i>
        </div>
        <div class="form-group">
            <input type="password" name="password" id="password" class="form-control input-lg"
                   placeholder="{{ trans('validation.attributes.password') }}" autocomplete="off">
            <i class="fa fa-lock"></i>
        </div>
        <div class="row text-center">
            <button class="btn btn-lg btn-block btn-green"> {{ trans('front.login') }} </button>
            <div class="col-xs-12 mt10 mb10 f12">
                <a href="{{ url(\App\Providers\SettingServiceProvider::getLocale() . '/password/reset') }}"
                   class="link link-blue">
                    {{ trans('people.form.recover_password') }}
                </a>
            </div>
            <div class="col-xs-12 mt10 mb10 f12">
                <div class="row">
                    <a type="button" href="{{ url('/register') }}"
                       class="btn btn-block btn-blue"> {{ trans('front.not_member_register_now') }} </a>
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
    </form>
@endsection

@section('endOfBody')
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