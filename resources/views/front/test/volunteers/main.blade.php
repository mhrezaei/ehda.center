@extends('front.frame.frame')

@section('head')
    <title>{{ setting()->ask('site_title')->gain() }} | {{ trans('front.special_volunteers') }}</title>
@endsection

@section('content')
    <style>
        .ehda-card {
            display: none
        }
    </style>
    <style type="text/css">
        ul.waterfall li {
            left: 0;
            top: 0;
            opacity: 0;
            z-index: 0;
        }

        ul.waterfall li.show {
            opacity: 1;
            transition: all 0.3s, top 1s;
        }

        ul.waterfall li > div {
            transition: all 0.5s;
        }

        /***************************************/

        .yt-gallery ul.waterfall {
            padding: 0;
        }

        .yt-gallery ul.waterfall .yt-gallery-item img {
            width: 100%;
        }

    </style>
    <div class="container-fluid">

        @include('front.frame.position_info', [
            'group' => 'تماس با ما',
            'groupColor' => 'green',
        ])

        <div class="container content">
            <div class="yt-gallery">
                <ul id="waterfall" class="waterfall">
                    @for($i = 0; $i < 200; $i++)
                        @include('front.test.volunteers.item')
                    @endfor
                </ul>
            </div>
        </div>
    </div>
@section('endOfBody')
    {!! Html::script ('assets/js/jquery.lazyload.min.js') !!}
    {!! Html::script ('assets/js/newWaterfall.js') !!}
    <script type="text/javascript">
        $(function () {
            var itemsSelector = '.yt-gallery-item';
            var imagesSelectorTxt = 'img.lazy';
            var items = $(itemsSelector);
            var imageElements = items.find(imagesSelectorTxt);
            imageElements.lazyload();

            items.mousemove(function () {
                doFocus($(this));
            });
            items.mouseleave(function () {
                items.removeClass('focus');
                items.removeClass('unfocus');
            });

            $('#waterfall').NewWaterfall({
                width: 150,
                delay: 100,
            });
        });
        function doFocus(element) {
            if (element.find('img.yt-gallery-item-img').hasClass('loaded')) {
                element.addClass('focus');
                element.siblings('.yt-gallery-item').addClass('unfocus')
            }
        }
    </script>
@endsection
@endsection
