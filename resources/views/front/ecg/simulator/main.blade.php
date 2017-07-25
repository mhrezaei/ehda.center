@extends('front.ecg.frame.frame')

@section('head')
    {{ Html::style('assets/css/ecg-simulator.min.css') }}
@append

@section('body')
    <div class="container-main">
        <div class="container-inner monitor">
            <div class="col-lg-2 col-md-3 col-xs-12 monitor-column-1">
                <div class="monitor-vital-sign neonPink">
                    <div class="monitor-vital-sign-heading">
                        <div class="monitor-vital-sign-title">B.P</div>
                        <div class="monitor-vital-sign-unit">mmHg/mmHg</div>
                    </div>
                    <div class="monitor-vital-sign-body">
                        <div class="monitor-vital-sign-value preview-bp"></div>
                    </div>
                </div>
                <div class="monitor-vital-sign neonGreen">
                    <div class="monitor-vital-sign-heading">
                        <div class="monitor-vital-sign-title">H.R</div>
                        <div class="monitor-vital-sign-unit">bpm</div>
                    </div>
                    <div class="monitor-vital-sign-body">
                        <div class="monitor-vital-sign-value preview-hr"></div>
                    </div>
                </div>
                <div class="monitor-vital-sign neonYellow">
                    <div class="monitor-vital-sign-heading">
                        <div class="monitor-vital-sign-title">R.R</div>
                        <div class="monitor-vital-sign-unit">bpm</div>
                    </div>
                    <div class="monitor-vital-sign-body">
                        <div class="monitor-vital-sign-value preview-rr"></div>
                    </div>
                </div>
                <div class="monitor-vital-sign darkOrange">
                    <div class="monitor-vital-sign-heading">
                        <div class="monitor-vital-sign-title">SpO<sub>2</sub></div>
                        <div class="monitor-vital-sign-unit">%</div>
                    </div>
                    <div class="monitor-vital-sign-body">
                        <div class="monitor-vital-sign-value preview-spo2"></div>
                    </div>
                </div>
                <div class="monitor-vital-sign cyan">
                    <div class="monitor-vital-sign-heading">
                        <div class="monitor-vital-sign-title">CVP</div>
                        <div class="monitor-vital-sign-unit">mmHg</div>
                    </div>
                    <div class="monitor-vital-sign-body">
                        <div class="monitor-vital-sign-value preview-cvp"></div>
                    </div>
                </div>
                <div class="monitor-vital-sign neonRed">
                    <div class="monitor-vital-sign-heading">
                        <div class="monitor-vital-sign-title">Temp</div>
                        <div class="monitor-vital-sign-unit">&#8451;</div>
                    </div>
                    <div class="monitor-vital-sign-body">
                        <div class="monitor-vital-sign-value preview-temperature"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-10 col-md-9 col-xs-12 monitor-column-2">
                <div class="monitor-ecg-preview">
                    <div class="monitor-ecg-preview-inner"></div>
                </div>
                <div class="monitor-ecg-shock-box" style="display: none">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-md-4 col-sm-6 col-xs-12">
                                <select class="form-control input-sm shocker-energy">
                                    <option value="200">200 J</option>
                                    <option value="360">360 J</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-sm-6 col-xs-12">
                                <button type="button" class="btn btn-sm btn-orange btn-charge-shocker">CHARGE</button>
                            </div>
                            <div class="col-md-4 col-sm-6 col-xs-12">
                                <button type="button" class="btn btn-sm btn-red btn-shock" disabled="disabled">
                                    SHOCK
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 pt10 shocker-charger-box" style="opacity: 0;">
                        <div class="progress shocker-charger">
                            <div class="progress-bar progress-bar-warning progress-bar-striped active"
                                 role="progressbar"
                                 aria-valuemin="0" aria-valuemax="100" style="width:0%">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="monitor-case-management-panel">
                    <div class="monitor-case-management-panel-inner">
                        <div id="start" class="page current" data-step="Start">
                            <div class="biography">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <h2>Case 1</h2>
                                        <p class="text-justify case-biography"></p>
                                        <p class="text-justify">
                                            <b>Height:</b> <span class="case-height"></span>
                                            <br/>
                                            <b>Weight:</b> <span class="case-weight"></span>
                                            <br/>
                                            <b>Urine Output:</b> <span class="case-urine-output"></span> cc/hour
                                        </p>
                                        <div class="col-xs-12 text-center pt20 mb30">
                                            <button type="button" class="btn btn-lg pass-step">START</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="start-question" class="page" data-step="StartQuestion">
                            <div class="page-content">
                                <b>Question 1: What would you do at this moment?</b>
                                <br/>
                                <ol type="a">
                                    <li>
                                        <div class="radio">
                                            <input type="radio" id="q1-1" name="start-question" value="1"/>
                                            <label for="q1-1">
                                                Get some more information from ICU nurses and case family.
                                            </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="radio">
                                            <input type="radio" id="q1-2" name="start-question" value="2"/>
                                            <label for="q1-2">
                                                Order some paraclinical Exam.
                                            </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="radio">
                                            <input type="radio" id="q1-3" name="start-question" value="3"/>
                                            <label for="q1-3">
                                                Order some treatment modalities
                                            </label>
                                        </div>
                                    </li>
                                </ol>
                                <div class="col-xs-12 mt10 text-right">
                                    <button type="button" class="btn pass-step">NEXT</button>
                                </div>
                            </div>
                        </div>

                        <div id="more-info" class="page">
                            <div class="page-content">
                                <h3>More Information</h3>
                                <p class="text-justify case-more-information"></p>
                                <div class="col-xs-12 text-left pt20">
                                    <button type="button" class="btn back-step">BACK</button>
                                </div>
                            </div>
                        </div>

                        <div id="laboratory-exams" class="page page-fluid">
                            <ul class="nav nav-tabs nav-justified">
                                <li class="active">
                                    <a data-toggle="tab" href="#laboratory-exams-cbc">CBC</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#laboratory-exams-biochemistry">Biochemistry</a></li>
                                <li>
                                    <a data-toggle="tab" href="#laboratory-exams-coagulation-tests">Coagulation Test</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#laboratory-exams-agb">ABG</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#laboratory-exams-urine-analysis">Urine Analysis</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#laboratory-exams-echocardiography">Echocardiography</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#laboratory-exams-virology-tests">Virology Test</a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div id="laboratory-exams-cbc" class="tab-pane fade in active">
                                    <table class="f22">
                                        <thead>
                                        <tr>
                                            <td colspan="3">CBC</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Hgb</td>
                                            <td><span class="case-cbc-hgb"></span></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>HCT</td>
                                            <td><span class="case-cbc-hct"></span></td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>WBC</td>
                                            <td><span class="case-cbc-wbc"></span></td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>Platelet</td>
                                            <td><span class="case-cbc-platelet"></span></td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>ESR</td>
                                            <td><span class="case-cbc-esr"></span></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div id="laboratory-exams-biochemistry" class="tab-pane fade">
                                    <table class="f22">
                                        <thead>
                                        <tr>
                                            <td colspan="3">Biochemistry</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Blood Sugar</td>
                                            <td><span class="case-biochemistry-blood-sugar"></span></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>BUN</td>
                                            <td><span class="case-biochemistry-bun"></span></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Cr</td>
                                            <td><span class="case-biochemistry-cr"></span></td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>Na</td>
                                            <td><span class="case-biochemistry-na"></span></td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>K</td>
                                            <td><span class="case-biochemistry-k"></span></td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td>Ca</td>
                                            <td><span class="case-biochemistry-ca"></span></td>
                                        </tr>
                                        <tr>
                                            <td>7</td>
                                            <td>P</td>
                                            <td><span class="case-biochemistry-p"></span></td>
                                        </tr>
                                        <tr>
                                            <td>8</td>
                                            <td>Mg</td>
                                            <td><span class="case-biochemistry-mg"></span></td>
                                        </tr>
                                        <tr>
                                            <td>9</td>
                                            <td>AST</td>
                                            <td><span class="case-biochemistry-ast"></span></td>
                                        </tr>
                                        <tr>
                                            <td>10</td>
                                            <td>ALT</td>
                                            <td><span class="case-biochemistry-alt"></span></td>
                                        </tr>
                                        <tr>
                                            <td>11</td>
                                            <td>ALKp</td>
                                            <td><span class="case-biochemistry-alkp"></span></td>
                                        </tr>
                                        <tr>
                                            <td>12</td>
                                            <td>Albumin</td>
                                            <td><span class="case-biochemistry-albumin"></span></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div id="laboratory-exams-coagulation-tests" class="tab-pane fade">
                                    <table class="f22">
                                        <thead>
                                        <tr>
                                            <td colspan="3">Coagulation Tests</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>PT</td>
                                            <td><span class="case-coagulation-tests-pt"></span></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>PTT</td>
                                            <td><span class="case-coagulation-tests-ptt"></span></td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>INR</td>
                                            <td><span class="case-coagulation-tests-inr"></span></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div id="laboratory-exams-agb" class="tab-pane fade">
                                    <table class="f22">
                                        <thead>
                                        <tr>
                                            <td colspan="3">ABG</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>PH</td>
                                            <td><span class="case-agb-ph"></span></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>PCO<sub>2</sub></td>
                                            <td><span class="case-agb-pco2"></span></td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>HCO<sub>3</sub></td>
                                            <td><span class="case-agb-hco3"></span></td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>PO<sub>2</sub></td>
                                            <td><span class="case-agb-po2"></span></td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>O<sub>2</sub>Sat</td>
                                            <td><span class="case-agb-o2sat"></span>%</td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td>Lactate</td>
                                            <td><span class="case-agb-lactate"></span></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div id="laboratory-exams-urine-analysis" class="tab-pane fade">
                                    <table class="f22">
                                        <thead>
                                        <tr>
                                            <td colspan="3">Urine Analysis</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Specific Gravity</td>
                                            <td><span class="case-urine-analysis-specific-gravity"></span></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Urine Osmolality</td>
                                            <td><span class="case-urine-analysis-urine-osmolality"></span></td>
                                        </tr>
                                        <tr>
                                            <td>WBC</td>
                                            <td>WBC</td>
                                            <td><span class="case-urine-analysis-wbc"></span></td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>RBC</td>
                                            <td><span class="case-urine-analysis-rbc"></span></td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>Sugar</td>
                                            <td><span class="case-urine-analysis-sugar"></span></td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td>protein</td>
                                            <td><span class="case-urine-analysis-protein"></span></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div id="laboratory-exams-echocardiography" class="tab-pane fade">
                                    <table class="f22">
                                        <thead>
                                        <tr>
                                            <td colspan="3">Echocardiography</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>EF</td>
                                            <td><span class="case-echocardiography-ef"></span>%</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Wall Motion Abnormality</td>
                                            <td><span class="case-echocardiography-wall-motion-abnormality"></span></td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Valvular Problems</td>
                                            <td><span class="case-echocardiography-valvular-problems"></span></td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>PAP</td>
                                            <td><span class="case-echocardiography-pap"></span></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div id="laboratory-exams-virology-tests" class="tab-pane fade">
                                    <table class="f22">
                                        <thead>
                                        <tr>
                                            <td colspan="3">Virology Tests</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>HBsAg</td>
                                            <td><span class="case-virology-tests-hbsag"></span></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>HBsAb</td>
                                            <td><span class="case-virology-tests-hbsab"></span></td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>HBcAb</td>
                                            <td><span class="case-virology-tests-hbcab"></span></td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>HBeAb</td>
                                            <td><span class="case-virology-tests-hbcab"></span></td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>HCVAb</td>
                                            <td><span class="case-virology-tests-hcvab"></span></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-xs-12 text-left pt20 mb20">
                                <button type="button" class="btn back-step">BACK</button>
                            </div>
                        </div>

                        <div id="treatment-modalities" class="page page-fluid" data-step="TreatmentModalities"
                             data-showing-action="needToFIO2()">
                            <ul class="nav nav-tabs nav-justified">
                                <li class="active">
                                    <a data-toggle="tab" href="#treatment-modalities-fluid">1. Fluid</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#treatment-modalities-vasopressor">2. Vasopressor</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#treatment-modalities-ventilator">3. Ventilator</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#treatment-modalities-temperature">4. Temperature</a>
                                </li>
                                <li>
                                    <a data-toggle="tab" href="#treatment-modalities-medications">5. Medications</a>
                                </li>
                            </ul>

                            <div class="tab-content">
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
                                <div id="treatment-modalities-vasopressor" class="tab-pane fade">
                                    <h3>2-1. Type of the Vasopressor</h3>
                                    <div class="form-horizontal treatment-form" data-treatment="2-1">
                                        <div class="col-xs-12">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                                    <div class="form-group">
                                                        <div class="col-xs-12">
                                                            <select class="form-control" name="2-1" id="t-2-1">
                                                                <option></option>
                                                                <option value="1">Dopamine</option>
                                                                <option value="2">Norepinephrine</option>
                                                                <option value="3">Vasopressin</option>
                                                                <option value="4">Epinephrine</option>
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
                                <div id="treatment-modalities-medications" class="tab-pane fade">
                                    <h3>5-1. Desmopressin</h3>
                                    <div class="form-horizontal treatment-form" data-treatment="5-1">
                                        <div class="col-xs-12">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                                    <div class="form-group">
                                                        <div class="col-xs-12">
                                                            <select class="form-control" name="5-1" id="t-5-1">
                                                                <option></option>
                                                                <option value="1">Nasal Spray</option>
                                                                <option value="2">Subcutaneous</option>
                                                                <option value="3">IV bullous</option>
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
                                    <h3>5-2. Ca</h3>
                                    <div class="form-horizontal treatment-form" data-treatment="5-2">
                                        <div class="col-xs-12">
                                            <div class="row">
                                                <input type="hidden" name="5-2" id="t-5-2" value="1"/>
                                                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                                    <div class="row">
                                                        <div class="col-xs-12 mb15">
                                                            @include('front.ecg.simulator.apply-button')
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h3>5-3. Packed Cell</h3>
                                    <div class="form-horizontal treatment-form" data-treatment="5-3">
                                        <div class="col-xs-12">
                                            <div class="row">
                                                <input type="hidden" name="5-3" id="t-5-3" value="1"/>
                                                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                                    <div class="row">
                                                        <div class="col-xs-12 mb15">
                                                            @include('front.ecg.simulator.apply-button')
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h3>5-4. FFP</h3>
                                    <div class="form-horizontal treatment-form" data-treatment="5-4">
                                        <div class="col-xs-12">
                                            <div class="row">
                                                <input type="hidden" name="5-4" id="t-5-4" value="1"/>
                                                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                                    <div class="row">
                                                        <div class="col-xs-12 mb15">
                                                            @include('front.ecg.simulator.apply-button')
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h3>5-5. Albumin</h3>
                                    <div class="form-horizontal treatment-form" data-treatment="5-5">
                                        <div class="col-xs-12">
                                            <div class="row">
                                                <input type="hidden" name="5-5" id="t-5-5" value="1"/>
                                                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                                    <div class="row">
                                                        <div class="col-xs-12 mb15">
                                                            @include('front.ecg.simulator.apply-button')
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h3>5-6. K</h3>
                                    <div class="form-horizontal treatment-form" data-treatment="5-6">
                                        <div class="col-xs-12">
                                            <div class="row">
                                                <input type="hidden" name="5-6" id="t-5-6" value="1"/>
                                                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                                    <div class="row">
                                                        <div class="col-xs-12 mb15">
                                                            @include('front.ecg.simulator.apply-button')
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h3>5-7. Insulin</h3>
                                    <div class="form-horizontal treatment-form" data-treatment="5-7">
                                        <div class="col-xs-12">
                                            <div class="row">
                                                <input type="hidden" name="5-7" id="t-5-7" value="1"/>
                                                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                                    <div class="row">
                                                        <div class="col-xs-12 mb15">
                                                            @include('front.ecg.simulator.apply-button')
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="second-preview" style="display: none">
                        <div class="col-md-9 xol-xs-12 mb10">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    Urine Output: <span class="preview-uop"></span> cc/hour
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    HgB: <span class="preview-hgb"></span>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    INR: <span class="preview-inr"></span>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    BS: <span class="preview-bs"></span>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    Na: <span class="preview-na"></span>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    K: <span class="preview-k"></span>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    Ca: <span class="preview-ca"></span>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    PH: <span class="preview-ph"></span>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    PCO<sub>2</sub>: <span class="preview-pco2"></span>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    HCO<sub>3</sub>: <span class="preview-hco3"></span>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    PO<sub>2</sub>: <span class="preview-po2"></span>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    Albumin: <span class="preview-albumin"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 xol-xs-12 case-info-buttons">
                            <div class="row">
                                <div class="col-md-12 col-sm-6 col-xs-12 mb10">
                                    <button class="btn btn-block show-case-info" data-page="#more-info">More Info
                                    </button>
                                </div>
                                <div class="col-md-12 col-sm-6 col-xs-12 mb10">
                                    <button class="btn btn-block show-case-info" data-page="#laboratory-exams">Exams
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="cover-page">
        <div class="radial-timer s-animate">
            <div class="radial-timer-half"></div>
            <div class="radial-timer-half"></div>
        </div>
    </div>
@append

@section('end-of-body')
    {{ Html::script('assets/libs/ecg/simulator.js') }}
@append