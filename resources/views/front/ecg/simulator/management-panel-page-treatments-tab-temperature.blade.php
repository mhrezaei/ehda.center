<div id="treatment-modalities-temperature" class="tab-pane fade">
    <h3>4-1. Warming the patient</h3>
    <div class="form-horizontal treatment-form" data-treatment="4-1">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <select class="form-control" name="4-1" id="t-4-1" multiple>
                                <option value="1">Nothing</option>
                                <option value="2">Warmer Blanket</option>
                                <option value="3">Serum Warmer</option>
                                <option value="4">Ventilator Humidifier</option>
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