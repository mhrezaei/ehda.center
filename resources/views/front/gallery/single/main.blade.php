@extends('front.frame.frame')

@php $pageTitle = setting()->ask('site_title')->gain() . ' | ' . trans('front.gallery') @endphp
@section('head')
    <title>{{ $pageTitle }}</title>
@append
@php $metaTags = [ 'title' => $pageTitle ] @endphp
@include('front.frame.meta_tags')
@include('front.frame.open_graph_meta_tags')

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
        <div class="row">
            <div class="flex_wrapper" id="gallery">
                @for($i = 0; $i < 21; $i++)
                    @include('front.gallery.single.item')
                @endfor
            </div>
        </div>
    </div>
    @include('front.gallery.single.scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.flex-item img').css('opacity', 0).on('load', function(){
                $(this).css('opacity', '1');
            });
            $("#gallery a").featherlightGallery({
                openSpeed: 300
            });
            $.featherlightGallery.prototype.afterContent = function() {
                var caption = this.$currentTarget.find('img').attr('alt');
                this.$instance.find('.featherlight-caption').remove();
                $('<h4 class="featherlight-caption text-right">').text(caption).appendTo(this.$instance.find('.featherlight-content'));
            };
        });
    </script>
@endsection