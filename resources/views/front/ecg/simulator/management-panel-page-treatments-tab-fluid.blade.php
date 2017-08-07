<div id="treatment-modalities-fluid" class="tab-pane fade in active">
    <h3>1-1. Stat</h3>
    <div class="form-horizontal treatment-form" data-treatment="1-1">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <label class="control-label">
                                1-1-1. Type of the Fluid:
                            </label>
                            <select class="form-control" name="1-1-1" id="t-1-1-1"
                                    data-relted="#t-1-1-2">
                                <option></option>
                                <option value="1">Serum Normal Saline</option>
                                <option value="2">Serum Half Saline</option>
                                <option value="3">Serum DW5%</option>
                                <option value="4">Water Gavage</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group" style="display: none;">
                        <div class="col-xs-12">
                            <label class="control-label">
                                1-1-2. Amount of Fluid:
                            </label>
                            <select class="form-control" name="1-1-2" id="t-1-1-2">
                                <option value="1">1 lit</option>
                                <option value="2">2 lit</option>
                                <option value="3">3 lit</option>
                                <option value="4">4 lit</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                    <label class="control-label">&nbsp;</label>
                    <div class="row">
                        <div class="col-xs-12">
                            @include('front.ecg.simulator.apply-button')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <h3>1-2. Maintenance</h3>
    <div class="form-horizontal treatment-form" data-treatment="1-2">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <label class="control-label">
                                1-2-1. Type of the Fluid:
                            </label>
                            <select class="form-control" name="1-2-1" id="t-1-2-1"
                                    data-relted="#t-1-2-2">
                                <option></option>
                                <option value="1">Serum Normal Saline</option>
                                <option value="2">Serum Half Saline</option>
                                <option value="3">Serum DW5%</option>
                                <option value="4">Water Gavage</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group" style="display: none;">
                        <div class="col-xs-12">
                            <label class="control-label">
                                1-2-2. Amount of Fluid:
                            </label>
                            <select class="form-control" name="1-2-2" id="t-1-2-2">
                                <option value="1">200-300 cc/q3h</option>
                                <option value="2">300-400 cc/q3h</option>
                                <option value="3">400-500 cc/q3h</option>
                                <option value="4">500-600 cc/q3h</option>
                                <option value="5">600-700 cc/q3h</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                    <label class="control-label">&nbsp;</label>
                    <div class="row">
                        <div class="col-xs-12">
                            @include('front.ecg.simulator.apply-button')
                            <button type="button" class="btn"
                                    onclick="$('#reserved-maintenance').slideDown(); $(this).hide()">
                                                                <span class="glyphicon glyphicon-plus"
                                                                      aria-hidden="true"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="reserved-maintenance" class="form-horizontal treatment-form"
         data-treatment="1-2" style="display: none;">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <label class="control-label">
                                1-2-1. Type of the Fluid:
                            </label>
                            <select class="form-control" name="1-2-1" id="t2-1-2-1"
                                    data-relted="#t2-1-2-2">
                                <option></option>
                                <option value="1">Serum Normal Saline</option>
                                <option value="2">Serum Half Saline</option>
                                <option value="3">Serum DW5%</option>
                                <option value="4">Water Gavage</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group" style="display: none;">
                        <div class="col-xs-12">
                            <label class="control-label">
                                1-2-2. Amount of Fluid:
                            </label>
                            <select class="form-control" name="1-2-2" id="t2-1-2-2">
                                <option value="1">200-300 cc/q3h</option>
                                <option value="2">300-400 cc/q3h</option>
                                <option value="3">400-500 cc/q3h</option>
                                <option value="4">500-600 cc/q3h</option>
                                <option value="5">600-700 cc/q3h</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                    <label class="control-label">&nbsp;</label>
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