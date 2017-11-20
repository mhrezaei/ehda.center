<!DOCTYPE html>
<html lang="en">
<head>
    @php $post->spreadMeta() @endphp
    @php $featuredImageUrl = \App\Providers\UploadServiceProvider::changeFileUrlVersion($post->viewable_featured_image, 'original'); @endphp
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $post->title }}</title>

    <meta property="og:title" content="{{ $post->title }}" />
    <meta property="og:url" content="https://telegram.me/Ehdayeozv_bot" />
    <meta property="og:image" content="{{ $featuredImageUrl }}" />
{{--    <meta property="og:description" content="{{ $post->title }}" />--}}

    <!-- Bootstrap -->
    {{ Html::style('assets/css/landing/bootstrap.min.css') }}
    {{ Html::style('assets/css/landing/bootstrap.rtl.min.css') }}
    {{ Html::style('assets/css/landing/fontiran.css') }}
    {{ Html::style('assets/css/landing/TimeCircles.css') }}
    {{ Html::style('assets/css/landing/style.min.css') }}

    {{--<link href="css/bootstrap.min.css" rel="stylesheet">--}}
    {{--<link rel="stylesheet" href="css/bootstrap.rtl.min.css">--}}
    {{--<link media="all" type="text/css" rel="stylesheet" href="css/fontiran.css">--}}
    {{--<link href="css/TimeCircles.css" rel="stylesheet">--}}
    {{--<link rel="stylesheet" href="css/style.min.css">--}}

    <style>
        *:not(.fa), html, body{
            font-family: "IRANSans" !important;
            /*direction: rtl;*/
        }
        .textDiv_Days span, .textDiv_Days h4,
        .textDiv_Hours span, .textDiv_Hours h4,
        .textDiv_Minutes span, .textDiv_Minutes h4,
        .textDiv_Seconds span, .textDiv_Seconds h4{
            font-weight: normal !important;
            font-style: normal !important;
            line-height: 40px !important;
            font-family: 'IRANSans' !important;
        }
        .main {
{{--            background: url("{{ url('assets/images/template/landing/summer-hand.png') }}"), url("{{ url('assets/images/template/landing/summer.jpg') }}");--}}
{{--            background: url("{{ $featuredImageUrl }}");--}}
                        background: url(), url("{{ $featuredImageUrl }}");

            background-position: right bottom, center;
            background-repeat: no-repeat, no-repeat;
            background-size: 20% auto, cover;
            background-attachment: fixed, scroll;
        }

    </style>
</head>
<body>
<div class="wrapper">
    <!--header-->
    <header>
        <div class="container-fluid">
            <a class="title-container" href="https://ehda.center">
                <img src="https://ehda.center/uploads/posts/gallery/image/1503245522_M4PSsReOLsElg7A7X8TP95luaFEE92_original.png"
                     alt="" class="header-logo">
            </a>
        </div>
    </header>
    <!--end header-->

    <!--main-->
    <div class="main">
        <div class="color-offset"></div>
        <div class="container main-container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="content">
                        <div class="inner-content">
                            <h2 class="color-title">
                                {{ $post->title }}
                            </h2>
                            <p class="center ltr">
                                {{--<a href="https://telegram.me/Ehdayeozv_bot" class="telegram-link" target="_blank">@Ehdayeozv_bot</a>--}}
                                {{--<br />--}}
                                <a href="https://ehda.center" class="telegram-link" target="_blank">www.ehda.center</a>
                            </p>
                            @php $sentences = explode("\n", $post->sentences) @endphp
                            @if(trim($post->sentences) and $sentences and is_array($sentences) and count($sentences))
                                <h3 class="headline shutter-slide">
                                    <div class="word-wrapper">
                                        <span class="shutter">
                                            @foreach($sentences as $sentence)
                                                <b class="">{{ $sentence }}</b>
                                            @endforeach
                                            {{--<b class="">ثبت حماسه&zwnj;ای دیگر از نوع&zwnj;دوستی ایرانیان در فصل تابستان</b>--}}
                                            {{--<b class="">هر ایرانی یک سفیر اهدای عضو</b>--}}
                                            {{--<b class="">تست تست تست تست تست تست تست</b>--}}
                                        </span>
                                    </div>
                                </h3>
                            @endif
                            <div class="count-wrapper">
                                <h2 style="font-size: 2.5em;">
                                    {{ $post->title2 }}
                                    <strong class="s_counter_today" style=" color: #5A9B20; display: none;"></strong>
                                </h2>
                                <h3 style="display: none;">
                                    از ابتدای برنامه
                                    <strong class="s_counter_total" style=" color: #5A9B20;"></strong>
                                    نفر در این کمپین شرکت کرده‌اند.
                                </h3>
                            </div>
                            @if($post->ends_at)
                                <div class="container-fluid">
                                        <div class="timer countdown-time bounceIn" data-date="{{ landingPageTimer($post, 'desc') }}"
                                    {{--<div class="timer countdown-time bounceIn" data-date="2017-09-20 19:45:00"--}}
                                         data-timer="1000">

                                    </div>
                                </div>
                            @endif
                            <div class="btn-wrapper">
                                <a href="https://fundorun.com/campaign/1000216" target="_blank"
                                   class="btn btn-lg btn-subscribe btn-custom"
                                   role="button">حمایت از پویش مدال یادبود فرشتگان ماندگار</a>
                            </div>
                        </div>
                    </div>
                    <!--footer-->
                    <footer>
                        <nav class="footbar">
                            <ul class="socials">
                                <li>
                                    <a href="https://www.facebook.com/ehdapage" class="social-link facebook">
                                        <i class="fa fa-facebook" aria-hidden="true"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://telegram.me/ehda_center" class="social-link telegram">
                                        <i class="fa fa-telegram" aria-hidden="true"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://www.instagram.com/ehda.center" class="social-link instagram">
                                        <i class="fa fa-instagram" aria-hidden="true"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://ehda.center/" class="social-link site">
                                        <i class="fa fa-sitemap" aria-hidden="true"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                        <p class="copyright">

                            حقوق مادی و معنوی این سایت برای
                            <a href="https://ehda.center" target="_blank">انجمن اهدای عضو ایرانیان</a>

                            محفوظ است.
                            <br/>
                            <a href="https://yasnateam.com" target="_blank" style="font-size: 10px; color: #9AACC1;">طراحی
                                و اجرا: گروه یسنا</a>
                        </p>
                    </footer>
                    <!--end footer-->
                </div>
            </div>
        </div>
        <nav class="sidebar">
            <ul class="socials">
                <li>
                    <a href="https://www.facebook.com/ehdapage" class="social-link facebook">
                        <i class="fa fa-facebook" aria-hidden="true"></i>
                    </a>
                </li>
                <li>
                    <a href="https://telegram.me/ehda_center" class="social-link telegram">
                        <i class="fa fa-telegram" aria-hidden="true"></i>
                    </a>
                </li>
                <li>
                    <a href="https://www.instagram.com/ehda.center" class="social-link instagram">
                        <i class="fa fa-instagram" aria-hidden="true"></i>
                    </a>
                </li>
                <li>
                    <a href="https://ehda.center/" class="social-link site">
                        <i class="fa fa-sitemap" aria-hidden="true"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <!-- end main -->


</div>
<!--end of wrapper-->

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script>
    window.jQuery || document.write('<script src="js/jquery-3.2.1.min.js">\x3C/script>')
</script>
{!! Html::script ('assets/libs/bootstrap/js/bootstrap.min.js') !!}
{{--<script src="js/bootstrap.min.js"></script>--}}
<script src="https://use.fontawesome.com/f4fcfb493d.js"></script>
{!! Html::script ('assets/js/landing/TimeCircles.js') !!}
{{--<script type="text/javascript" src="js/TimeCircles.js"></script>--}}
{!! Html::script ('assets/js/landing/custom.js') !!}
{{--<script src="js/custom.js"></script>--}}
</body>
</html>