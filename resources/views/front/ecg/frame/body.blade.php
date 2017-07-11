<body>
<div class="container pt40">
    <div class="row-flex">
        <div class="monitor">
            <div class="row-flex">
                <div class="monitor-control-panel-container col-xs-3">
                    <div class="monitor-control-panel col-xs-12">
                        @yield('control-panel')
                    </div>
                </div>

                <div class="monitor-preview-container col-xs-9">
                    <div class="monitor-preview col-xs-12">
                        <div class="row-flex">
                            <div class="col-xs-3">
                                <div class="monitor-preview-info col-xs-12">
                                    <div class="row">
                                        <div class="monitor-preview-info-box pink">
                                            <div class="monitor-preview-info-title">B.P</div>
                                            <div class="monitor-preview-info-value bp-value">120/80</div>
                                        </div>
                                        <div class="monitor-preview-info-box">
                                            <div class="monitor-preview-info-title">H.R</div>
                                            <div class="monitor-preview-info-value hr-value">{{ $heartBeat }}</div>
                                        </div>
                                        <div class="monitor-preview-info-box yellow">
                                            <div class="monitor-preview-info-title">R.R</div>
                                            <div class="monitor-preview-info-value bp-value">16</div>
                                        </div>
                                        <div class="monitor-preview-info-box green">
                                            <div class="monitor-preview-info-title">SPO2</div>
                                            <div class="monitor-preview-info-value bp-value">30</div>
                                        </div>
                                        <div class="monitor-preview-info-box cyan">
                                            <div class="monitor-preview-info-title">CVP</div>
                                            <div class="monitor-preview-info-value bp-value">7</div>
                                        </div>
                                        <div class="monitor-preview-info-box red">
                                            <div class="monitor-preview-info-title">T</div>
                                            <div class="monitor-preview-info-value bp-value">34</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-9" style="display: block">
                                <div class="row">
                                    <div class="monitor-preview-diagram-container col-xs-12">
                                        <div class="monitor-preview-diagram">
                                            @yield('diagram')
                                        </div>
                                    </div>
                                </div>
                                @yield('extra')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('front.ecg.frame.scripts')
@yield('end-of-body')
</body>