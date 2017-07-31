/**
 * Created by Yasna-PC1 on 19/07/2017.
 */

window.caseId = 1;
window.reactions = [];
window.timers = {};
window.shockerCharge = 0;
window.currentData = {};

$(document).ready(function () {
    correctView();

    $.ajaxSetup({cache: false});

    lowLag.init({'urlPrefix': siteUrl + '/assets/sound/'});
    lowLag.load(['heartSound.mp3', 'heartSound.ogg'], 'pluck1');
    lowLag.load(['beep.mp3'], 'beep');
    lowLag.load(['charging.mp3', 'charging.waw'], 'charging');
    lowLag.load(['electricShock.mp3', 'electricShock.waw'], 'electricShock');

    loadData();

    $(window).resize(function () {
        correctView();
    });

    $('.monitor-column-2')
        .children()
        .not('.monitor-case-management-panel')
        .on('show', function () {
            correctControlPanelHeight();
        })
        .on('hide', function () {
            correctControlPanelHeight();
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

    $('.treatment-form').on({
        click: function (event) {
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
                                setCaseData(targetName, Number(result.toFixed(2)));
                                tmpAction.after = getValueOf(window.currentData)[targetName];
                                tmpAction.change = tmpAction.after - tmpAction.before;

                                doneActions.push(tmpAction);
                            });
                        }
                    });
                });

                var lastReaction = window.reactions.last();
                var pushingNumber = window.reactions.push({
                    fromStep: lastReaction.toStep,
                    toStep: lastReaction.toStep,
                    fromPage: lastReaction.toPage,
                    toPage: lastReaction.toPage,
                    forward: true,
                    calculations: doneActions,
                    beforeData: beforeData,
                    afterData: getValueOf(window.currentData),
                    treatment: treatment,
                    treatmentData: data
                });

                btn.removeClass('btn-inject').addClass('btn-inject-remove');
                btn.attr('data-reaction', pushingNumber - 1);
                btn.html('Remove');

                refreshScreen();

                if (!$('.second-preview').is(':visible')) {
                    $('.second-preview').show();
                }
            }
        }
    }, '.btn-inject');

    $('.treatment-form').on({
        click: function (event) {
            event.preventDefault();
            var btn = $(this);

            var reactionIndex = btn.attr('data-reaction');
            backStep(undefined, reactionIndex);

            btn.removeClass('btn-inject-remove').addClass('btn-inject');
            btn.removeAttr('data-reaction');
            btn.html('Apply');

            refreshScreen();
        }
    }, '.btn-inject-remove');

    $('.monitor-ecg-shock-box').find('.btn-charge-shocker').click(function () {
        var energy = $('.shocker-energy').val();
        chargeShocker(energy);
    });

    $('.monitor-ecg-shock-box').find('.btn-shock').click(function () {
        doShock();
    });

    $('.show-case-info').click(function () {
        var btn = $(this);
        var target = btn.attr('data-page');
        showPage($(target));
        refreshScreen()
    });
});

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
        setCaseData(caseData.defaultData);

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
        eval(window.tmpReaction.toPage.attr('data-showing-action'));
        console.log(window.reactions)
    }
    //
    // refreshScreen();
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
                var targetPage = $('#more-info');
                targetPage.addClass('current');

                window.tmpReaction.toStep = targetPage.attr('data-step');
                window.tmpReaction.toPage = targetPage;

                console.log(currentTime())
                pageTimeout($('#more-info'), window.timeouts.moreInfo, ventricularTachycardia);
                break;
            case "2":
                var targetPage = $('#laboratory-exams');
                targetPage.addClass('current');

                window.tmpReaction.toStep = targetPage.attr('data-step');
                window.tmpReaction.toPage = targetPage;

                console.log(currentTime())
                pageTimeout($('#laboratory-exams'), window.timeouts.exams, ventricularTachycardia);
                break;
            case "3":
                var targetPage = $('#treatment-modalities');
                targetPage.addClass('current');

                window.tmpReaction.toStep = targetPage.attr('data-step');
                window.tmpReaction.toPage = targetPage;

                targetPage.find('select').each(function () {
                    $(this).val($(this).children('option').first().val());
                });

                break;
        }

        window.tmpReaction.done = true;
    }
}

function showPage(pageToShow) {
    var page = $('.page.current');

    window.reactions.push({
        fromStep: page.attr('data-step'),
        fromPage: page,
        toStep: pageToShow.attr('data-step'),
        toPage: pageToShow,
        forward: true,
        done: false,
        beforeData: getValueOf(window.currentData),
        afterData: getValueOf(window.currentData),
    });

    page.removeClass('current');
    pageToShow.addClass('current');
}

function backStep(page, returningStepIndex) {
    if (typeof returningStepIndex == 'undefined') {
        returningStepIndex = window.reactions.length - 1;
        var inverseColumn = window.reactions.getColumn('inverse');

        // Last step should be a step with "auto=false" or first consecutive in the end of steps
        var lastStep = window.reactions[returningStepIndex];
        while (!lastStep.forward || // If found step is a backing step
        (lastStep.auto && window.reactions[returningStepIndex - 1].auto) || // If found step is auto and isn't the first consecutive auto
        ($.inArray(returningStepIndex, inverseColumn) > -1) // If we didn't get back from found step
            ) {
            returningStepIndex--;
            lastStep = window.reactions[returningStepIndex];
        }

    } else {
        var lastStep = window.reactions[returningStepIndex];
    }

    if (typeof page != 'undefined') {
        var timeoutName = page.attr('timeout');
        if (timeoutName) {
            clearTimeout(window.timers[timeoutName]);
        }
    }

    if (lastStep.auto) {
        var newData = getValueOf(lastStep.beforeData);
    } else {
        var newData = {};
        $.each(lastStep.beforeData, function (key, value) {
            var change = lastStep.afterData[key] - lastStep.beforeData[key];
            var result = getValueOf(window.currentData, key) - change;
            result = result.toFixed(2);
            result = Number(result);
            newData[key] = result;
        });
    }

    setCaseData(newData);
    var newReaction = {
        fromStep: getValueOf(lastStep, 'toStep'),
        fromPage: lastStep.toPage,
        toStep: getValueOf(lastStep, 'fromStep'),
        toPage: lastStep.fromPage,
        forward: false,
        inverse: returningStepIndex,
        beforeData: getValueOf(window.reactions.last().afterData),
        afterData: getValueOf(window.currentData),
    };

    newReaction.fromPage.removeClass('current');
    newReaction.toPage.addClass('current');

    if (window.reactionForceData && $.isPlainObject(window.reactionForceData)) {
        $.each(window.reactionForceData, function (key, value) {
            newReaction[key] = value;
        });
        delete window.reactionForceData;
    }

    window.reactions.push(newReaction);

    eval(newReaction.toPage.attr('data-showing-action'));

    refreshScreen();

    return returningStepIndex;
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
    var changing = {
        HR: 210,
        SPO2: 20,
        SBP: 0,
        DBP: 0,
        CVP: 8,
    };
    var calculations = [];
    var beforeData = getValueOf(window.currentData);

    $('.monitor-ecg-preview-inner').addClass('VTach');

    $.each(changing, function (name, value) {
        calculations.push({
            before: getValueOf(window.currentData)[name],
            after: value,
            target: name
        });
    });
    setCaseData(changing);

    var lastReaction = window.reactions.last();
    window.reactions.push({
        fromStep: lastReaction.toStep,
        toStep: lastReaction.toStep,
        fromPage: lastReaction.toPage,
        toPage: lastReaction.toPage,
        forward: true,
        auto: true,
        calculations: calculations,
        beforeData: lastReaction.afterData,
        afterData: getValueOf(window.currentData),
    });


    refreshScreen();

    $('.monitor-ecg-shock-box').find('.btn-shock').attr('disabled', 'disabled');
    $('.monitor-ecg-shock-box').show();
    $('.monitor-case-management-panel').hide();

    window.timers.die = setTimeout(kill, window.timeouts.VTack);
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
    $('.preview-albumin').html(window.currentData.Albumin);

    runECG(window.currentData.HR);
    playHeartSound(window.currentData.HR);

    if ($('#more-info').hasClass('current') || $('#laboratory-exams').hasClass('current')) {
        $('.case-info-buttons').hide();
    } else if (!$('.case-info-buttons').is(':visible')) {
        $('.case-info-buttons').show();
    }
}

function runECG(hr) {
    stopECG();
    var ecgBox = $('.monitor-ecg-preview-inner');

    var url = ecgBox.css('background-image').match(/^url\("?(.+?)"?\)$/)

    if (url[1]) {
        url = url[1];
        image = new Image();

        // just in case it is not already loaded
        $(image).on('load', function () {
            var periodWidth = (image.width * ecgBox.height()) / image.height / 20;
            runECGPeriod(hr, periodWidth);

            window.timers.ecgMotion = setInterval(function () {
                runECGPeriod(hr, periodWidth)
            }, 60000);
        });

        image.src = url;
    }
}

function runECGPeriod(hr, periodWidth) {
    $('.monitor-ecg-preview-inner').animate({
        'background-position-x': '-=' + (hr * periodWidth) + 'px'
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

    var chargingTime = (energy / chargingSpeed) * 2000; // Miliseconds

    playSound('charging');
    box.css('opacity', 1);

    progressBar.animate({width: "100%"}, chargingTime, function () {
        $('.monitor-ecg-shock-box').find('.btn-shock').removeAttr('disabled');
        window.shockerCharge = energy;
        progressBar.attr('aria-valuenow', 100);
        $('.btn-charge-shocker').hide();
        $('.btn-shock').show();
    });
}

function doShock() {
    $('.monitor-ecg-preview-inner').removeClass('VTach').removeClass('dead');

    $('.shocker-charger-box').css('opacity', 0);
    $('.shocker-charger-box').find('.progress-bar').css('width', "0").attr('aria-valuenow', 0);

    if (true) { // check if energy is enough
        playSound('shock');
        clearTimeout(window.timers.die);
        delete window.timers.die;

        window.reactionForceData = {auto: true};
        backStep();
        delete window.reactionForceData;

        $('.monitor-case-management-panel').show();
        $('.monitor-ecg-shock-box').hide();
        $('.btn-charge-shocker').show();
        $('.btn-shock').hide();
    }
}

function currentTime() {
    var time = new Date();
    return time.getHours() + ":" + time.getMinutes() + ":" + time.getSeconds();
}

function kill() {
    console.log('die');
    console.log(currentTime());
    var changing = {
        HR: 0,
        SPO2: 20,
        SBP: 0,
        DBP: 0,
        CVP: 8,
    };
    var calculations = [];

    var beforeData = getValueOf(window.currentData);

    $('.monitor-ecg-preview-inner').removeClass('VTach')
        .addClass('dead');

    $.each(changing, function (name, value) {
        calculations.push({
            before: getValueOf(window.currentData)[name],
            after: value,
            target: name
        });
    });
    setCaseData(changing);

    var lastReaction = window.reactions.last();
    window.reactions.push({
        fromStep: lastReaction.toStep,
        toStep: lastReaction.toStep,
        fromPage: lastReaction.toPage,
        toPage: lastReaction.toPage,
        forward: true,
        auto: true,
        calculations: calculations,
        beforeData: beforeData,
        afterData: getValueOf(window.currentData),
    });

    refreshScreen();

    $('.monitor-ecg-shock-box').find('.btn-shock').attr('disabled', 'disabled');
    $('.monitor-ecg-shock-box').show();
    $('.monitor-case-management-panel').hide();
}

function getValueOf(data, key) {
    var data = JSON.parse(JSON.stringify(data));
    if (typeof key != 'undefined') {
        return data[key];
    }
    return data;
}

function playHeartSound(hr) {
    if (hr) {
        var intervalTime = (60 * 1000) / hr;
        var soundId = 'heartSound';
    } else {
        var intervalTime = 200;
        var soundId = 'beep';
    }

    stopHeartSound();
    window.timers.soundHR = setInterval(function () {
        playSound(soundId)
    }, intervalTime);
}

function stopHeartSound() {
    if (typeof window.timers.soundHR != 'undefined') {
        clearInterval(window.timers.soundHR);
        delete window.timers.soundHR;
    }
}

function playSound(id) {
    if (!window.optionsStatuses.playSound) {
        return;
    }
    switch (id) {
        case "heartSound":
            lowLag.play('pluck1', false);
            break;
        case "charging":
            lowLag.play('charging', false);
            break;
        case "shock":
            lowLag.play('electricShock', false);
            break;
        case "beep":
            lowLag.play('beep', false);
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

function setCaseData(param1, param2) {
    if ($.isPlainObject(param1)) {
        $.each(param1, function (name, value) {
            setCaseData(name, value);
        });
    } else {
        if (param1 == 'SPO2') {
            param2 = Math.min(param2, 100); // in percent
        }

        window.currentData[param1] = param2;
    }
}

function doneTreatments() {
    var inverted = window.reactions.getColumn('inverse');
    var result = [];
    $.each(window.reactions, function (i, val) {
        if (val.treatment && ($.inArray(i.toString(), inverted) == -1)) {
            result.push(val.treatment);
        }
    });
    return result;
}

function needToFIO2() {
    if (!checkFIO2()) {
        setTimeout(function () {
            if (!checkFIO2()) {
                ventricularTachycardia();
            }
        }, window.timeouts.needToFIO2);
    }
}

function checkFIO2() {
    var FIO2Key = '3-1';
    var done = doneTreatments();
    if ($.inArray(FIO2Key, done) > -1) { // FIO2 done
        return true;
    }
}

function correctView() {
    correctBodyHeight();
    correctVitalSingsHeight();
    correctControlPanelHeight();
}

/**
 * Make body height compatible with view port
 */
function correctBodyHeight() {
    var bodyHeight = $('body').height();
    var containerHeight = $('.container-main').height();

    if (containerHeight > bodyHeight) {
        $('body').height(containerHeight);
    } else {
        $('body').height("");
    }
}

function correctVitalSingsHeight() {
    var items = $('.monitor-vital-sign');
    var firstItem = items.first();
    var totalHeight = $('.monitor-column-1').height();
    var itemHeight = (totalHeight / items.length) - 2;

    var textHeight = itemHeight -
        firstItem.find('.monitor-vital-sign-heading').height() -
        parseInt(firstItem.find('.monitor-vital-sign-value').css('padding-top'));

    var fontSize = textHeight / window.styleConstants.line_height;

    items.find('.monitor-vital-sign-value').css('font-size', fontSize + 'px');
}

function correctControlPanelHeight() {
    var controlPanel = $('.monitor-case-management-panel');
    var siblings = controlPanel.siblings(':visible');
    var totalHeight = $('.monitor-column-2').height();

    var siblingsTotalHeight = 0;
    siblings.each(function () {
        var item = $(this);
        siblingsTotalHeight += (
            item.outerHeight() +
            parseInt(item.css('margin-top')) +
            parseInt(item.css('margin-bottom'))
        );
    });

    var availableHeight = totalHeight - siblingsTotalHeight;
    var controlPanelHeight = availableHeight - (
            parseInt(controlPanel.css('margin-top')) +
            parseInt(controlPanel.css('margin-bottom'))
        );
    // console.log('correctControlPanelHeight')
    // console.log(controlPanelHeight)

    controlPanel.height(controlPanelHeight);
}