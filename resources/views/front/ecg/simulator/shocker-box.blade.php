<div class="monitor-ecg-shock-box" style="display: none">
    <div class="col-xs-12">
        <div class="row">
            <div class="col-md-3 col-sm-8 col-xs-8">
                <select class="form-control input-sm shocker-energy">
                    <option value="200">200 J</option>
                    <option value="360">360 J</option>
                </select>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-4 text-center">
                <button type="button" class="btn btn-sm btn-orange btn-charge-shocker">CHARGE</button>
                <button type="button" class="btn btn-sm btn-red btn-shock" disabled="disabled"
                        style="display: none;">
                    SHOCK
                </button>
            </div>
            <div class="col-md-7 col-sm-12 col-xs-12 pt5">
                <div class="progress shocker-charger shocker-charger-box" style="opacity: 0;">
                    <div class="progress-bar progress-bar-warning progress-bar-striped active"
                         role="progressbar"
                         aria-valuemin="0" aria-valuemax="100" style="width:0%">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>