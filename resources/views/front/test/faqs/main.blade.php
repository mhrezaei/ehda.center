@extends('front.frame.frame')

@section('head')
    <title>{{ setting()->ask('site_title')->gain() }} | {{ trans('front.faqs') }}</title>
@endsection

@section('content')
    <style>
        .ehda-card {
            display: none
        }
    </style>
    <div class="container-fluid">
        @include('front.frame.position_info', [
            'group' => trans('front.faqs'),
            'groupColor' => 'green',
        ])
        <div class="container content">
            <div class="row">
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    {!! $faqsHTML !!}

                    @if($getNewFaq)
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingqs">
                                <h4 class="panel-title">
                                    <a class="collapsed link-blue" role="button" data-toggle="collapse"
                                       data-parent="#accordion"
                                       href="#collapseqs" aria-expanded="false" aria-controls="collapseqs">
                                        <span class="fa fa-question"></span> {{ trans('front.faq_not_found_ask_yours') }}
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseqs" class="panel-collapse collapse" role="tabpanel"
                                 aria-labelledby="headingqs">
                                <div class="panel-body">
                                    <div class="row">
                                        {!! $newFaqForm !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection