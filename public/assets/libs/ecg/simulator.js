/**
 * Created by Yasna-PC1 on 19/07/2017.
 */

window.caseId = 1;
window.reactions = [];
window.timers = {};
window.shockerCharge = 0;

$(document).ready(function () {

    lowLag.init({'urlPrefix': siteUrl + '/assets/sound/'});
    lowLag.load(['heartSound.mp3', 'heartSound.ogg'], 'pluck1');

    fixBodyHeight();
    loadData();

    $(window).resize(function () {
        fixBodyHeight();
    });

    $('.pass-step').click(function () {
        var page = $(this).closest('.page');
        passStep(page);
    });

    $('.back-step').click(function () {
        var page = $(this).closest('.page');
        backStep(page);
    });

    $('.treatment-form').find(':input').change(function () {
        var that = $(this);
        var related = that.attr('data-relted');
        if (related) {
            if (that.val()) {
                $(related).closest('.form-group').show();
            } else {
                $(related).closest('.form-group').hide();
            }
        }
    });

    $('.treatment-form').find('.btn-inject').click(function (event) {
        event.preventDefault();
        var btn = $(this);
        var box = btn.closest('.treatment-form');
        var treatment = box.attr('data-treatment');
        var data = box.find(':input').serializeObject();

        if (!Object.values(data).includes("")) { // check for empty value
            var equations = window.caseData.calculations;
            var toDo = eval('equations' + dash2brace(treatment));
            var doneActions = [];
            var beforeData = getValueOf(window.currentData);

            $.each(toDo, function (num, tasks) {
                if (num) {
                    var checkingField = [treatment, num].join('-');
                } else {
                    var checkingField = treatment;
                }

                if (!$.isArray(data[checkingField])) {
                    data[checkingField] = [data[checkingField]];
                }

                $.each(tasks, function (val, targets) {
                    if ($.inArray(val, data[checkingField]) > -1) {
                        $.each(targets, function (targetName, equation) {
                            $.each(data, function (fieldName, fieldValue) {
                                equation = equation.replace(new RegExp('{{' + fieldName + '}}', 'g'), fieldValue);
                            });

                            var tmpAction = {
                                target: targetName,
                                equation: equation,
                                before: getValueOf(window.currentData)[targetName]
                            };

                            equation = equation.replace(new RegExp('{{orig}}', 'g'), window.currentData[targetName]);

                            var result = eval(equation);
                            window.currentData[targetName] = Number(result.toFixed(2));
                            tmpAction.after = getValueOf(window.currentData)[targetName];

                            doneActions.push(tmpAction);
                        });
                    }
                });
            });

            var lastReaction = window.reactions.last();
            window.reactions.push({
                fromStep: lastReaction.toStep,
                toStep: lastReaction.toStep,
                fromPage: lastReaction.toPage,
                toPage: lastReaction.toPage,
                forward: true,
                calculations: doneActions,
                beforeData: beforeData,
                afterData: getValueOf(window.currentData),
            });

            refreshScreen();

            if (!$('.second-preview').is(':visible')) {
                $('.second-preview').slideDown();
            }

            btn.hide();
        }
    });

    $('.monitor-ecg-shock-box').find('.btn-charge-shocker').click(function () {
        var energy = $('.shocker-energy').val();
        chargeShocker(energy);

    });

    $('.monitor-ecg-shock-box').find('.btn-shock').click(function () {
        doShock();
    });
});

/**
 * Make body height compatible with view port
 */
function fixBodyHeight() {
    var bodyHeight = $('body').height();
    var containerHeight = $('.container-main').height();

    if (containerHeight > bodyHeight) {
        $('body').height(containerHeight);
    } else {
        $('body').height("");
    }
}

function dash2brace(string) {
    var parts = string.split('-');
    var result = '';

    $.each(parts, function (index, item) {
        result += '[' + item + ']';
    });

    return result;
}

/**
 * Get cases data to calculate in steps
 */
function loadData() {
    loading();

    $.getJSON(siteUrl + "/files/ecg/json/cases.json", function (data) {
        var caseData = data[window.caseId];

        window.caseData = caseData;
        window.currentData = caseData.defaultData;

        $('.case-biography').html(caseData.caseInfo.information);
        $('.case-height').html(caseData.caseInfo.height);
        $('.case-weight').html(caseData.caseInfo.weight);
        $('.case-urine-output').html(caseData.caseInfo.urineOutput);
        $('.case-more-information').html(caseData.caseInfo.moreInformation);

        $.each(caseData.exams, function (tableTitle, tableData) {
            $.each(tableData, function (key, data) {
                var targetClass = ['case', tableTitle, key].join(' ').title2kebab();
                $('.' + targetClass).html(data);
            });
        });

        refreshScreen();

        // window.calculations = data[window.caseId][defaultData];
        loading("hide");
    });
}

/**
 * Show or hide lading box
 * @param task
 */
function loading(task) {
    var loadingCover = $('.cover-page');
    if (typeof task != 'undefined' || task == 'hide') {
        loadingCover.fadeOut("slow");
    } else {
        loadingCover.fadeIn("slow");
    }
}

function passStep(page) {
    var stepName = page.attr('data-step');

    window.tmpReaction = {
        fromStep: stepName,
        fromPage: page,
        forward: true,
        done: false,
        beforeData: getValueOf(window.currentData),
        afterData: getValueOf(window.currentData),
    };

    // Call related function dynamically
    var functionName = "pass" + stepName + "Step";
    window[functionName](page);

    // Record the reaction if it is completed
    if (window.tmpReaction.done) {
        delete window.tmpReaction.done;
        window.reactions.push(window.tmpReaction);
        console.log(window.reactions)
    }
}

function passStartStep() {
    $('#start').removeClass('current');
    $('#start-question').addClass('current');
    window.tmpReaction.toStep = $('#start-question').attr('data-step');
    window.tmpReaction.toPage = $('#start-question');
    window.tmpReaction.done = true;
}

function passStartQuestionStep(page) {
    var val = $('input[type=radio][name=start-question]:checked').val();

    if (val) {
        $('#start-question').removeClass('current');

        switch (val) {
            case "1":
                $('#more-info').addClass('current');

                window.tmpReaction.toStep = $('#more-info').attr('data-step');
                window.tmpReaction.toPage = $('#more-info');

                console.log(currentTime())
                pageTimeout($('#more-info'), 30 * 1000, ventricularTachycardia);
                break;
            case "2":
                $('#laboratory-exams').addClass('current');

                window.tmpReaction.toStep = $('#laboratory-exams').attr('data-step');
                window.tmpReaction.toPage = $('#laboratory-exams');

                console.log(currentTime())
                pageTimeout($('#laboratory-exams'), 60 * 1000, ventricularTachycardia);
                break;
            case "3":
                $('#treatment-modalities').addClass('current');

                window.tmpReaction.toStep = $('#treatment-modalities').attr('data-step');
                window.tmpReaction.toPage = $('#treatment-modalities');

                $('#treatment-modalities').find('select').each(function () {
                    $(this).val($(this).children('option').first().val());
                });

                break;
        }

        window.tmpReaction.done = true;
    }
}

function backStep(page) {
    var lastStepIndex = window.reactions.length - 1;
    var lastStep = window.reactions[lastStepIndex];
    console.log(lastStep);

    while (!lastStep.forward || lastStep.auto) {
        lastStepIndex--;
        lastStep = window.reactions[lastStepIndex];
    }
    console.log(lastStep)

    if (typeof page != 'undefined') {
        var timeoutName = page.attr('timeout');
        if (timeoutName) {
            clearTimeout(window.timers[timeoutName]);
        }
    }

    window.currentData = lastStep.beforeData;
    var newReaction = {
        fromStep: lastStep.toStep,
        fromPage: lastStep.toPage,
        toStep: lastStep.fromStep,
        toPage: lastStep.fromPage,
        forward: false,
        beforeData: lastStep.afterData,
        afterData: getValueOf(window.currentData),
    };

    refreshScreen();

    newReaction.fromPage.removeClass('current');
    newReaction.toPage.addClass('current');

    window.reactions.push(newReaction);
}

function pageTimeout(page, time, timeoutAction) {
    var timeoutName = "timeout" + $.now();

    window.timers[timeoutName] = setTimeout(function () {
        if (page.hasClass('current')) {
            timeoutAction();
        }
    }, time);

    page.attr('timeout', timeoutName);
}

function ventricularTachycardia() {
    console.log('VTach');
    console.log(currentTime());
    var newHR = 210;
    var beforeData = getValueOf(window.currentData);
    console.log('beforeData')
    console.log(beforeData)

    $('.monitor-ecg-preview-inner').addClass('VTach');

    var lastReaction = window.reactions.last();
    window.reactions.push({
        fromStep: lastReaction.toStep,
        toStep: lastReaction.toStep,
        fromPage: lastReaction.toPage,
        toPage: lastReaction.toPage,
        forward: true,
        auto: true,
        calculations: [
            {
                before: getValueOf(window.currentData).HR,
                after: newHR,
                target: 'HR'
            }
        ],
        beforeData: beforeData,
        afterData: getValueOf(window.currentData),
    });

    window.currentData.HR = newHR;

    refreshScreen();

    $('.monitor-ecg-shock-box').find('.btn-shock').attr('disabled', 'disabled');
    $('.monitor-ecg-shock-box').show();
    $('.monitor-case-management-panel').hide();

    window.timers.die = setTimeout(kill, 60 * 1000);
}

function refreshScreen() {
    $('.preview-bp').html(window.currentData.SBP + '/' + window.currentData.DBP);
    $('.preview-hr').html(window.currentData.HR);
    $('.preview-rr').html(window.currentData.RR);
    $('.preview-spo2').html(window.currentData.SPO2);
    $('.preview-cvp').html(window.currentData.CVP);
    $('.preview-temperature').html(window.currentData.T);
    $('.preview-uop').html(window.currentData.UOP);
    $('.preview-hgb').html(window.currentData.HgB);
    $('.preview-inr').html(window.currentData.INR);
    $('.preview-bs').html(window.currentData.BS);
    $('.preview-na').html(window.currentData.Na);
    $('.preview-k').html(window.currentData.K);
    $('.preview-ca').html(window.currentData.Ca);
    $('.preview-ph').html(window.currentData.PH);
    $('.preview-pco2').html(window.currentData.PCO2);
    $('.preview-hco3').html(window.currentData.HCO3);
    $('.preview-po2').html(window.currentData.PO2);

    runECG(window.currentData.HR);
    // playHeartSound(window.currentData.HR);
}

function runECG(hr) {
    stopECG();
    runECGPeriod(hr);
    window.timers.ecgMotion = setInterval(function () {
        runECGPeriod(hr);
    }, 60 * 1000);
}

function runECGPeriod(hr) {
    $('.monitor-ecg-preview-inner').animate(
        {
            'background-position-x': '+=' + hr + '%'
        }, 60000, 'linear');
}

function stopECG(hr) {
    clearInterval(window.timers.ecgMotion);
    $('.monitor-ecg-preview-inner').stop();
    delete window.timers.ecgMotion;
}

function chargeShocker(energy) {
    var chargingSpeed = 100; // Joule/Second
    var box = $('.shocker-charger-box');
    var progressBar = box.find('.progress-bar');

    var chargingTime = (energy / chargingSpeed) * 1000; // Miliseconds

    box.css('opacity', 1);

    progressBar.animate({width: "100%"}, chargingTime, function () {
        $('.monitor-ecg-shock-box').find('.btn-shock').removeAttr('disabled');
        window.shockerCharge = energy;
        progressBar.attr('aria-valuenow', 100);
    });
}

function doShock() {
    $('.monitor-ecg-preview-inner').removeClass('VTach').removeClass('dead');

    $('.shocker-charger-box').css('opacity', 0);
    $('.shocker-charger-box').find('.progress-bar').css('width', "0").attr('aria-valuenow', 0);

    if (true) { // check if energy is enough
        clearTimeout(window.timers.die);
        delete window.timers.die;
        backStep();

        $('.monitor-case-management-panel').show();
        $('.monitor-ecg-shock-box').hide();
    }
}

function currentTime() {
    var time = new Date();
    return time.getHours() + ":" + time.getMinutes() + ":" + time.getSeconds();
}

function kill() {
    console.log('die');
    console.log(currentTime());
    var newHR = 0;
    var beforeData = getValueOf(window.currentData);
    console.log('beforeData')
    console.log(beforeData)

    $('.monitor-ecg-preview-inner').removeClass('VTach')
        .addClass('dead');

    var lastReaction = window.reactions.last();
    window.reactions.push({
        fromStep: lastReaction.toStep,
        toStep: lastReaction.toStep,
        fromPage: lastReaction.toPage,
        toPage: lastReaction.toPage,
        forward: true,
        auto: true,
        calculations: [
            {
                before: getValueOf(window.currentData).HR,
                after: newHR,
                target: 'HR'
            }
        ],
        beforeData: beforeData,
        afterData: getValueOf(window.currentData),
    });

    window.currentData.HR = newHR;

    refreshScreen();

    $('.monitor-ecg-shock-box').find('.btn-shock').attr('disabled', 'disabled');
    $('.monitor-ecg-shock-box').show();
    $('.monitor-case-management-panel').hide();
}

function getValueOf(data) {
    return JSON.parse(JSON.stringify(data));
}

function playHeartSound(hr) {
    var intervalTime = (60 * 1000) / hr;

    window.timers.soundHR = setInterval(playSound('heartSound'), interval);
}

function playSound(id) {
    console.log('playing')
    switch (id) {
        case "heartSound":
            lowLag.play('pluck1');
            break;
        case "wrong":
            boing.play();
            break;
        case "right":
            right1.play();
            break;
        case "wellDone":
            wellDone.play();
            break;
        case "helpMe":
            helpMe.play();
            break;
        case "congrats":
            congrats.play();
            break;

    }
}