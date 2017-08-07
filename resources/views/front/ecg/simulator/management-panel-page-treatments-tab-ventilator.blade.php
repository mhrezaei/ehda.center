<div id="treatment-modalities-ventilator" class="tab-pane fade">
    <h3>3-1. FIO2</h3>
    <div class="form-horizontal treatment-form" data-treatment="3-1">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <select class="form-control" name="3-1" id="t-3-1">
                                <option></option>
                                <option value="1">40%</option>
                                <option value="2">60%</option>
                                <option value="3">80%</option>
                                <option value="4">100%</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                    <div class="row">
                        <div class="col-xs-12">
                            @include('front.ecg.simulator.apply-button')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <h3>3-2. Respiratory Rate</h3>
    <div class="form-horizontal treatment-form" data-treatment="3-2">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <select class="form-control" name="3-2" id="t-3-2">
                                <option></option>
                                <option value="1">12</option>
                                <option value="2">14</option>
                                <option value="3">16</option>
                                <option value="4">18</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                    <div class="row">
                        <div class="col-xs-12">
                            @include('front.ecg.simulator.apply-button')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>