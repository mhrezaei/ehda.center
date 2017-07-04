@extends('front.frame.frame')

@section('head')
    <title>
        {{ setting()->ask('site_title')->gain() }}
        |
        {{ trans('front.profile_phrases.user_profile', ['user' => user()->full_name]) }}
    </title>
    <style>
        .tab-content .tab-pane img {
            height: 300px;
        }

        .tab-content .tab-pane {
            padding: 10px 15px;
        }

        .tab-content .tab-pane img {
            object-fit: contain;
            margin: auto;
            padding: 10px;
        }

        .a2a_kit {
            display: flex;
            justify-content: center;
        }
    </style>
@append

@section('content')
    {{ null, $positionInfo = (isset($positionInfo) and is_array($positionInfo)) ? $positionInfo : [] }}
    <div class="container-fluid">
        @include('front.frame.position_info', [
            'group' => trans('front.main-menu.items.join'),
            'groupColor' => 'green',
            'category' => trans('front.profile_phrases.profile'),
            'categoryColor' => 'green',
            'title' => user()->full_name,
        ] + $positionInfo )
        <div class="container content pb20">
            <div class="row mt40">
                <div class="col-md-6 col-xs-12">
                    {{ null, $cardTypes = array_keys(trans('front.organ_donation_card_section.types')) }}
                    @foreach($cardTypes as $key => $cardType)
                    @section('nav-tabs')
                        <li @if($key == 0) class="active" @endif data-card-type="{{ $cardType }}">
                            <a data-toggle="tab" href="#card-{{ $cardType }}"
                               data-content="{{ trans('front.organ_donation_card_section.types.' . $cardType . '.description') }}"
                               class="has-popover-tab" data-card-type="{{ $cardType }}">
                                {{ trans('front.organ_donation_card_section.types.' . $cardType . '.title') }}
                            </a>
                        </li>
                    @append
                    @section('tab-content')
                        <div id="card-{{ $cardType }}" class="tab-pane fade @if($key == 0) in active @endif"
                             data-card-type="{{ $cardType }}">
                            <div class="row">
                                <div class="col-xs-12">
                                    <img src="{{ user()->cards($cardType) }}"
                                         class="img-responsive border-1 border-lightGray">
                                </div>
                            </div>
                        </div>
                    @append
                    @endforeach
                    <ul class="nav nav-tabs nav-justified">
                        @yield('nav-tabs')
                    </ul>

                    <div class="tab-content cars-tab-content">
                        @yield('tab-content')
                    </div>

                    <div class="col-xs-12 mt10 text-center">
                        <a class="btn btn-blue download-btn" href="{{ user()->cards() }}"
                           target="_blank">
                            <i class="fa fa-download"></i>
                            &nbsp;
                            {{ trans('front.download') }}
                        </a>
                        <a class="btn btn-blue" href="{{ user()->cards('full', 'print') }}"
                           target="_blank">
                            <i class="fa fa-print"></i>
                            &nbsp;
                            {{ trans('front.print') }}
                        </a>
                        <a class="btn btn-blue share-btn" href="#">
                            <i class="fa fa-share-alt"></i>
                            &nbsp;
                            {{ trans('front.share') }}
                        </a>
                    </div>

                    <div class="col-xs-12 mt10 share" style="display: none">
                        @include('front.frame.widgets.add-to-any', [
                            'url' => user()->cards('social')
                        ])
                    </div>
                </div>
                <div class="col-md-6 col-xs-12 pt45 pr45 f18 text-justify">
                    <div class="row">
                        <div class="col-xs-12">
                            @if($profilePost and $profilePost->text)
                                <p>{!! $profilePost->text !!}</p>
                            @endif
                        </div>
                        <div class="col-xs-12 align-vertical-center align-horizontal-center">
                            <div class="col-xs-5">
                                <a href="{{ route_locale('user.profile.edit') }}"
                                   class="btn btn-blue btn-block">
                                    {{ trans('front.member_section.profile_edit') }}
                                </a>
                            </div>
                            <div class="col-xs-5">
                                {{-- @TODO: should make its link to go to suitable link for voluteer --}}
                                <a href="#"
                                   class="btn btn-blue btn-block">
                                    {{ trans('front.volunteer_section.plural') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@append

@section('endOfBody')
    <script>
        $(document).ready(function () {
            // Defining popover
            $('.has-popover-tab').popover({
                trigger: 'manual',
                placement: 'top',
                animate: true,
                delay: 500,
                container: 'body'
            }).first().popover('show');

            // On change tab
            $(document).on('shown.bs.tab', '.has-popover-tab', function (e) {
                var tabBtn = $(e.target);
                var tabLink = tabBtn.attr('href');
                var activeTabPane = $($(tabLink));

                // Showing popover for selected tab
                if (!$('#' + tabBtn.attr('aria-describedby') + '.popover:visible').length) {
                    // popover is visible
                    tabBtn.popover('show');
                }

                // Hiding popover for other tabs
                tabBtn.closest('li')
                    .siblings('li')
                    .children('.has-popover-tab')
                    .popover('hide');

                // Changing "href" attribute of ".download-btn"
                var img = activeTabPane.find('img');
                if (img.length) {
                    var url = img.attr('src').replace(/show(?!.*show)/, 'download');
                    $('.download-btn').attr('href', url);
                }

                if ($(this).data('cardType') == 'social') {
                    openSharing();
                } else {
                    closeSharing();
                }
            });

            $('.share-btn').click(function (e) {
                e.preventDefault();
                if (!$('.share').is(':visible')) {
                    $('a.has-popover-tab[data-card-type=social]').tab('show')
                }
            });
        });

        function openSharing() {
            $('.share').slideDown()
        }

        function closeSharing() {
            $('.share').slideUp()
        }
    </script>
@append

