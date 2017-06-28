<!DOCTYPE html>
<html class="fp-enabled" style="overflow: visible; height: initial;" lang="fa">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <meta property="og:title" content="انجمن اهدای عضو ایرانیان - کمپین ۱۰۰ هزار کارت اهدای عضو" />
    <meta property="og:url" content="https://telegram.me/Ehdayeozv_bot" />
    <meta property="og:image" content="https://ehda.center/assets/photos/posts/landing/logo-sq.jpg" />
    <meta property="og:description" content="ثبت حماسه ای دیگر از نوعدوستی ایرانیان درماه مبارک رمضان - هر ایرانی یک سفیر اهدای عضو" />

    <title>انجمن اهدای عضو ایرانیان - کمپین ۱۰۰ هزار کارت اهدای عضو</title>

    {!! Html::style('assets/landing/events/css.css') !!}
    {!! Html::style('assets/landing/events/bootstrap.css') !!}
    {!! Html::style('assets/landing/events/material-design-iconic-font.css') !!}
    {!! Html::style('assets/landing/events/ionicons.css') !!}
    {!! Html::style('assets/landing/events/headline.css') !!}
    {!! Html::style('assets/landing/events/jquery.css') !!}
    {!! Html::style('assets/landing/events/0style.css') !!}
{{--    {!! Html::style('assets/site/css/style.css') !!}--}}
    {!! Html::style('assets/landing/css/hadi.css') !!}
    {!! Html::style('assets/libs/bootstrap/css/bootstrap-rtl.min.css') !!}
    {!! Html::style('assets/libs/font-awesome/css/font-awesome.min.css') !!}
    {!! Html::style('assets/landing/events/style.css') !!}
    {!! Html::style('assets/landing/events/fontiran.css') !!}


    <style>
        *, html, body, .navbar-brand, .home-title, .demo, .time_circles, .scribe, .btn{
            font-family: "IranSans";
            /*direction: rtl;*/
        }
        body{
            /*margin-bottom: 25px !important;*/
        }
        .iranSans{
            font-family: "IranSans" !important;
        }
        .copyright{
            /*bottom: 5px !important;*/
            /*position: absolute;*/
            /*margin: 0 auto !important;*/
            /*text-align: justify;*/
            /*direction: rtl;*/
            /*width: 100%;*/
            /*padding-bottom: 0px;*/
            /*margin-bottom: 0px;*/
            /*padding-right: 15px;*/
            font-size: 12px !important;
        }
        header {
            /*background: #ffffff !important;*/
            margin: 0px !important;
            padding-top: 20px !important;
            padding-bottom: 10px !important;
            background: rgba(255, 255, 255, 0.7) !important;

        }
        .textDiv_Days span,
        .textDiv_Hours span,
        .textDiv_Minutes span,
        .textDiv_Seconds span{
            font-weight: normal !important;
            font-style: normal !important;
            line-height: 40px !important;
        }
        @media only screen and (max-width: 1024px) {

            body { font-size: 1em !important; }
            h3{
                font-size: 1.4em !important;
            }

            .demo{
                font-size: 25px !important;
                line-height: 35px !important;
            }

            h2, h2 strong{
                font-size: 25px !important;
                line-height: 30px !important;
            }

        }
        @media only screen and (max-width: 500px) {

            .textDiv_Days span,
            .textDiv_Hours span,
            .textDiv_Minutes span,
            .textDiv_Seconds span{
                font-weight: normal !important;
                font-style: normal !important;
                line-height: 10px !important;
            }

            h3{
                font-size: 1.2em !important;
            }

            .demo{
                font-size: 25px !important;
                line-height: 35px !important;
            }

            h2, h2 strong{
                font-size: 25px !important;
                line-height: 30px !important;
            }

        }

        .element {
            /*height: 250px;*/
            /*width: 250px;*/
            margin: 0 auto;
            /*background-color: red;*/
            animation-name: stretch;
            animation-duration: 2s;
            animation-timing-function: ease-out;
            animation-delay: 0;
            animation-direction: alternate;
            animation-iteration-count: infinite;
            animation-fill-mode: none;
            animation-play-state: running;
        }

        @keyframes stretch {
            0% {
                /*transform: scale(.3);*/
                color: #233D90;
                /*border-radius: 100%;*/
            }
            50% {
                color: #5A9B20;
            }
            100% {
                /*transform: scale(1.5);*/
                color: #EE6E73;
            }
        }

        .heartAn {
            /*animation-name: n;*/
            /*animation-duration: 1.3s;*/
            /*animation-timing-function: ease-out;*/
            /*animation-delay: 0;*/
            /*animation-direction: alternate;*/
            /*animation-iteration-count: infinite;*/
            /*animation-fill-mode: none;*/
            /*animation-play-state: running;*/
            animation: 1.3s ease 0s normal none infinite running n;
        }

        @keyframes heartbeat
        {
            0%
            {
                transform: scale( .75 );
            }
            20%
            {
                transform: scale( 1 );
            }
            40%
            {
                transform: scale( .75 );
            }
            60%
            {
                transform: scale( 1 );
            }
            80%
            {
                transform: scale( .75 );
            }
            100%
            {
                transform: scale( .75 );
            }
        }

        @-webkit-keyframes n {
            0% {
                -webkit-transform: scale(1);
                transform: scale(1)
            }
            14% {
                -webkit-transform: scale(1.3);
                transform: scale(1.3)
            }
            28% {
                -webkit-transform: scale(1);
                transform: scale(1)
            }
            42% {
                -webkit-transform: scale(1.3);
                transform: scale(1.3)
            }
            70% {
                -webkit-transform: scale(1);
                transform: scale(1)
            }
        }

        @keyframes n {
            0% {
                -webkit-transform: scale(1);
                transform: scale(1)
            }
            14% {
                -webkit-transform: scale(1.3);
                transform: scale(1.3)
            }
            28% {
                -webkit-transform: scale(1);
                transform: scale(1)
            }
            42% {
                -webkit-transform: scale(1.3);
                transform: scale(1.3)
            }
            70% {
                -webkit-transform: scale(1);
                transform: scale(1)
            }
        }
    </style>

</head>

<body class="image-background fp-viewing-1stPage" bg-image="{{ url('assets/photos/posts/landing/ramazan.jpg') }}" style="overflow: visible; height: initial;">

<!-- Preloader -->
<div id="preloader" style="display: none;">
    <div id="status" style="display: none;">
        <div class="spinner">
            Loading...
        </div>
    </div>
</div>
<!-- End Preloader -->

<header class="header">
    <nav class="navbar navbar-custom">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <a class="navbar-brand dark-logo" href="https://ehda.center" style="">
                    <img src="https://ehda.center/assets/site/images/header-logo.png" title="انجمن اهدای عضو ایرانیان" style="max-width: 60%;">
                </a>
            </div>
            {{--<a class="" href="https://ehda.center" style="float: left; direction: ltr;">--}}
                {{--<img src="https://ehda.center/assets/photos/posts/logo-9090-new-01.png" title="انجمن اهدای عضو ایرانیان" style="max-width: 49%; height: 49px;">--}}
                {{--<img src="https://ehda.center/assets/photos/posts/Bashgahe-Havadaran-A.png" title="انجمن اهدای عضو ایرانیان" style="max-width: 49%; height: 49px;">--}}
            {{--</a>--}}
        </div><!-- /.container-fluid -->
    </nav>
</header>

<div id="fullpage" style="height: 100%; position: relative;" class="fullpage-wrapper">
    <div class="overlay-demo"></div>
    <div class="section dark-image fp-section fp-table active fp-completely" id="section0" style="height: 947px; padding-top: 0px; padding-bottom: 80px;" data-anchor="1stPage">
        <div class="fp-tableCell" style="height:867px;">
            <div class="container">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="home-title section-title">
                            <p class="text-center demo element" style="font-family: 'IranSans'; font-size: 30px; line-height: 40px;">کمپین ۱۰۰ هزار کارت اهدای عضو در ماه مبارک رمضان</p>
                            <p class="text-center demo" style="font-family: 'IranSans'; font-size: 30px; color: #1A2865; line-height: 40px; direction: ltr;"><a href="https://telegram.me/Ehdayeozv_bot" target="_blank">@Ehdayeozv_bot</a></p>
                            <h3 class="cd-headline clip is-full-width">
                    <span class="cd-words-wrapper" style="width: auto; margin: 0 auto; padding-right: 10px; line-height: 40px; max-width: 90%;">
                        <b class="is-visible">ثبت حماسه‌ای دیگر از نوع‌دوستی ایرانیان درماه مبارک رمضان</b>
                        <b class="is-hidden">هر ایرانی یک سفیر اهدای عضو</b>
                        {{--<b class="is-visible">تا پایان ماه مبارک رمضان،</b>--}}
                        {{--<b class="is-hidden">می خواهیم تعداد ثبت نام کارت اهدای عضو را،</b>--}}
                        {{--<b class="is-hidden">از طریق بات تلگرام</b>--}}
                        {{--<b class="is-hidden">به ۱۰۰.۰۰۰ نفر برسانیم.</b>--}}
                        {{--<b class="is-hidden">شما هم می توانید در این کمپین مارا حمایت کنید.</b>--}}
                        {{--<b class="is-hidden">دریافت کارت اهدای عضو از طریق بات تلگرام،</b>--}}
                        {{--<b class="is-hidden">فقط <strong style="color: green;">۲ دقیقه</strong> زمان نیاز دارد.</b>--}}
                    </span>
                            </h3>
                            <div class="dark-time dark-circle animated bounceOut countdown-time mhrTimer" data-date="2017-06-25 23:59:59" data-timer="0" bg-color="#b4c4d4" circle-color="#ee6e73" data-tc-id="632c19f3-f3bf-df60-b48f-0c39f9846c9a">
                                <div class="time_circles mhrTimer" style="margin: 0 auto !important;">
                                    <canvas height="205" width="820"></canvas>
                                    <div class="textDiv_Days" style="top: 72px; left: 0px; width: 205px;">
                                        <h4 style="font-size: 14px; line-height: 14px;">روز</h4>
                                        <span style="font-size: 43px; line-height: 14px;">۳۰</span>
                                    </div>
                                    <div class="textDiv_Hours" style="top: 72px; left: 205px; width: 205px;">
                                        <h4 style="font-size: 14px; line-height: 14px;">ساعت</h4>
                                        <span style="font-size: 43px; line-height: 14px;">۰</span>
                                    </div>
                                    <div class="textDiv_Minutes" style="top: 72px; left: 410px; width: 205px;">
                                        <h4 style="font-size: 14px; line-height: 14px;">دقیقه</h4>
                                        <span style="font-size: 43px; line-height: 14px;">۰</span>
                                    </div>
                                    <div class="textDiv_Seconds" style="top: 72px; left: 615px; width: 205px;">
                                        <h4 style="font-size: 14px; line-height: 14px;">ثانیه</h4>
                                        <span style="font-size: 43px; line-height: 14px;">۰</span>
                                    </div>
                                </div>
                            </div>
                            <div class="scribe">
                                <h2 class="iranSans" style="color: #1A2865; line-height: 50px; font-size: 40px;">تا کنون <strong class="s_counter" style="font-size: 40px; color: #5A9B20;">{{ number_format($count) }}</strong> نفر در این کمپین شرکت کرده اند.</h2>
                            </div>

                            <div class="scribe" style="margin-top: 45px;">
                                <a href="https://telegram.me/Ehdayeozv_bot" class="btn btn-lg btn-subscribe dark-btn iranSans regBtn" role="button">ثبت نام از طریق بات تلگرام</a>
                            </div>
                        </div>
                    </div><!--End-col-->
                </div><!--End-row-->
            </div><!--End-container-->
        </div>
    </div><!-- End section -->

</div><!-- End Fullpage -->


<footer class="footer onstart animated dark-copyright" data-animation="fadeInUp" data-animation-delay="800">
    <nav class="social text-center">
        <ul>
            <li><a href="https://telegram.me/ehda_center" class="social-link facebook"><i class="fa fa-telegram" aria-hidden="true"></i></a></li>
            <li><a href="https://www.instagram.com/ehda.center/" class="social-link twitter"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
            <li><a href="http://www.aparat.com/ehda" class="social-link dribbble"><i class="fa fa-video-camera" aria-hidden="true"></i></a></li>
            <li><a href="https://ehda.center" class="social-link behance"><i class="fa fa-sitemap" aria-hidden="true"></i></a></li>
        </ul>
    </nav>
<div style="clear: both;">

</div>
    <p class="copyright iranSans" style="direction: rtl;">
        حقوق مادی و معنوی این سایت برای
        <a href="https://ehda.center" target="_blank">انجمن اهدای عضو ایرانیان</a>
         محفوظ است.
        <br>
        <a href="https://yasnateam.com" target="_blank" style="font-size: 10px; color: #9AACC1;">طراحی و اجرا: گروه یسنا</a>
    </p>

</footer>
{!! Html::script ('assets/landing/events/jquery-2.js') !!}
{!! Html::script ('assets/landing/events/bootstrap.js') !!}
{!! Html::script ('assets/landing/events/jquery.js') !!}
{!! Html::script ('assets/landing/events/jquery_003.js') !!}
{!! Html::script ('assets/landing/events/jquery_004.js') !!}
{!! Html::script ('assets/landing/events/jquery_006.js') !!}
{!! Html::script ('assets/landing/events/easypiechart.js') !!}
{!! Html::script ('assets/landing/events/animated-headline.js') !!}
{!! Html::script ('assets/landing/events/jquery_005.js') !!}
{!! Html::script ('assets/landing/events/jquery_002.js') !!}
{!! Html::script ('assets/landing/events/TimeCircles.js') !!}
{!! Html::script ('assets/landing/events/parsley.js') !!}
{!! Html::script ('assets/landing/events/custom.js') !!}

<div class="backstretch" style="left: 0px; top: 0px; overflow: hidden; margin: 0px; padding: 0px; height: 947px; width: 1903px; z-index: -999999; position: fixed;">
    <img style="position: absolute; margin: 0px; padding: 0px; border: medium none; width: 1903px; height: 1268.35px; max-height: none; max-width: none; z-index: -999999; left: 0px; top: -160.675px;" src="{{ url('assets/photos/posts/landing/ramazan.jpg') }}">
</div>
{!! Form::open() !!}

{!! Form::close() !!}
<script>
    $(document).ready(function () {
        $(".mhrTimer").TimeCircles({count_past_zero: false});
        rtl_text();
        counter();
    });

    function rtl_text()
    {
        $('.textDiv_Days h4').text('روز');
        $('.textDiv_Hours h4').text('ساعت');
        $('.textDiv_Minutes h4').text('دقیقه');
        $('.textDiv_Seconds h4').text('ثانیه');
        setTimeout(function () {
            rtl_text();
        }, 200);
    }
    
    function counter() {
        var tok = $('input[name=_token]').val();
        $.ajax({
            type: "POST",
            url: "ramazan",
            cache: false,
            dataType: "json",
            data: {
                _token: tok,
            }
        }).done(function(Data){
            if (Data.status == '1')
            {
                //$('.s_counter').html(Data.count);
                $('input[name=_token]').val(Data.sm);

                var count=Data.count;
                var start = 0;
                var g =  Math.ceil(count / 500);

                var scounter = setInterval(function () {
                    start = start + g;
                    if (start <= count)
                    {
                        $('.s_counter').text(number_format(start));
                    }
                    else
                    {
                        clearInterval(scounter);
                        $('.s_counter').text(number_format(count));
                    }
                }, 5);
                $('.regBtn').addClass('heartAn').delay(1000).removeClass('heartAn');
            }
        });
        setTimeout(function () {
            counter();
        }, 30000);
    }

    function number_format (number, decimals, decPoint, thousandsSep)
    { // eslint-disable-line camelcase
                                                                        //  discuss at: http://locutus.io/php/number_format/
                                                                        // original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
                                                                        // improved by: Kevin van Zonneveld (http://kvz.io)
                                                                        // improved by: davook
                                                                        // improved by: Brett Zamir (http://brett-zamir.me)
                                                                        // improved by: Brett Zamir (http://brett-zamir.me)
                                                                        // improved by: Theriault (https://github.com/Theriault)
                                                                        // improved by: Kevin van Zonneveld (http://kvz.io)
                                                                        // bugfixed by: Michael White (http://getsprink.com)
                                                                        // bugfixed by: Benjamin Lupton
                                                                        // bugfixed by: Allan Jensen (http://www.winternet.no)
                                                                        // bugfixed by: Howard Yeend
                                                                        // bugfixed by: Diogo Resende
                                                                        // bugfixed by: Rival
                                                                        // bugfixed by: Brett Zamir (http://brett-zamir.me)
                                                                        //  revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
                                                                        //  revised by: Luke Smith (http://lucassmith.name)
                                                                        //    input by: Kheang Hok Chin (http://www.distantia.ca/)
                                                                        //    input by: Jay Klehr
                                                                        //    input by: Amir Habibi (http://www.residence-mixte.com/)
                                                                        //    input by: Amirouche
                                                                        //   example 1: number_format(1234.56)
                                                                        //   returns 1: '1,235'
                                                                        //   example 2: number_format(1234.56, 2, ',', ' ')
                                                                        //   returns 2: '1 234,56'
                                                                        //   example 3: number_format(1234.5678, 2, '.', '')
                                                                        //   returns 3: '1234.57'
                                                                        //   example 4: number_format(67, 2, ',', '.')
                                                                        //   returns 4: '67,00'
                                                                        //   example 5: number_format(1000)
                                                                        //   returns 5: '1,000'
                                                                        //   example 6: number_format(67.311, 2)
                                                                        //   returns 6: '67.31'
                                                                        //   example 7: number_format(1000.55, 1)
                                                                        //   returns 7: '1,000.6'
                                                                        //   example 8: number_format(67000, 5, ',', '.')
                                                                        //   returns 8: '67.000,00000'
                                                                        //   example 9: number_format(0.9, 0)
                                                                        //   returns 9: '1'
                                                                        //  example 10: number_format('1.20', 2)
                                                                        //  returns 10: '1.20'
                                                                        //  example 11: number_format('1.20', 4)
                                                                        //  returns 11: '1.2000'
                                                                        //  example 12: number_format('1.2000', 3)
                                                                        //  returns 12: '1.200'
                                                                        //  example 13: number_format('1 000,50', 2, '.', ' ')
                                                                        //  returns 13: '100 050.00'
                                                                        //  example 14: number_format(1e-8, 8, '.', '')
                                                                        //  returns 14: '0.00000001'
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
        var n = !isFinite(+number) ? 0 : +number
        var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
        var sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep
        var dec = (typeof decPoint === 'undefined') ? '.' : decPoint
        var s = ''
        var toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec)
            return '' + (Math.round(n * k) / k)
                    .toFixed(prec)
        }
        // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.')
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || ''
            s[1] += new Array(prec - s[1].length + 1).join('0')
        }
        return s.join(dec)
    }
</script>

</body></html>