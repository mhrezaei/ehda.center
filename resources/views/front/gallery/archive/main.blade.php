@extends('front.frame.frame')

@php $pageTitle = setting()->ask('site_title')->gain() . ' | ' . $pageTitle @endphp
@section('head')
    <title>{{ $pageTitle }}</title>
@append
@php $metaTags = [ 'title' => $pageTitle ] @endphp
@include('front.frame.meta_tags')
@include('front.frame.open_graph_meta_tags')

@section('content')
    <div class="container-fluid">
        @include('front.frame.position_info', $positionInfo + [
            'groupColor' => 'green',
            'categoryColor' => 'green',
        ])
        <div class="row gallery-archive">
            <div class="container">
                <div class="row mt20 mb20">
                    @if($items and $items->count())
                        @foreach($items as $item)
                            @include('front.gallery.archive.item')
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

@section('endOfBody')
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
@append
@endsection