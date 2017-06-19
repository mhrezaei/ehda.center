@extends('front.frame.frame')

@section('head')
    <title>{{ setting()->ask('site_title')->gain() }} | {{ trans('front.gallery') }}</title>
@endsection

@section('content')
    <style>
        .ehda-card {
            display: none
        }
    </style>
    <div class="container-fluid">
        @include('front.frame.position_info', [
            'group' => 'توانستن',
            'category' => 'گالری',
            'title' => 'آرشیو',
        ])
        <div class="row gallery-archive">
            <div class="container">
                <div class="row mt20 mb20">
                    @for($i = 0; $i < 8; $i++)
                        @include('front.gallery.archive.item')
                    @endfor
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#gallery a").featherlightGallery({
                openSpeed: 300
            });
            $.featherlightGallery.prototype.afterContent = function () {
                var caption = this.$currentTarget.find('img').attr('alt');
                this.$instance.find('.featherlight-caption').remove();
                $('<h4 class="featherlight-caption text-right">').text(caption).appendTo(this.$instance.find('.featherlight-content'));
            };
        });
    </script>
@endsection