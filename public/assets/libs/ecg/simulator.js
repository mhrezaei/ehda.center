/**
 * Created by Yasna-PC1 on 19/07/2017.
 */

// Id of case will be read from cases.json file
window.caseId = 1;
// Every reaction that client makes will be stored in "window.reactions"
window.reactions = [];
// Every timers and timeouts will be stored in "window.timers"
window.timers = {};
// Level of shocker's charge
window.shockerCharge = 0;
// Current info of case will be accessible in "window.currentData"
window.currentData = {};

$(document).ready(function () {
    // Correct view (Fit view port size)
    correctView();

    // Force not to cache files in ajax request
    $.ajaxSetup({cache: false});

    // Load sounds
    lowLag.init({'urlPrefix': siteUrl + '/assets/sound/'});
    lowLag.load(['heartSound.mp3', 'heartSound.ogg'], 'pluck1');
    lowLag.load(['beep.mp3'], 'beep');
    lowLag.load(['charging.mp3', 'charging.wav'], 'charging');
    lowLag.load(['electricShock.mp3', 'electricShock.wav'], 'electricShock');

    // Load cases data
    loadData();

    /**
     ***********************************
     * Event Listeners
     ***********************************
     */

    // Correct view in every resize window
    $(window).resize(function () {
        correctView();
    });

    // If each item in right side column except "management panel" show or hide,
    // management panel height should be fixed again.
    $('.monitor-column-2')
        .children()
        .not('.monitor-case-management-panel')
        .on('show', function () {
            correctManagementPanelHeight();
        })
        .on('hide', function () {
            correctManagementPanelHeight();
        });

    $('.pass-step').click(function () {
        var page = $(this).closest('.page');
        passStep(page);
    });

    $('.back-step').click(function () {
        var page = $(this).closest('.page');
        backStep(page);
    });

    // Related to each other inputs in treatments page
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

    // Applying Treatments
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

                box.find(':input').attr('readonly', 'readonly');
                box.find('.form-group').css('pointer-events', 'none');
            }
        }
    }, '.btn-inject');

    // Removing treatments
    $('.treatment-form').on({
        click: function (event) {
            event.preventDefault();
            var btn = $(this);
            var box = btn.closest('.treatment-form');

            var reactionIndex = btn.attr('data-reaction');
            backStep(undefined, reactionIndex);

            btn.removeClass('btn-inject-remove').addClass('btn-inject');
            btn.removeAttr('data-reaction');
            btn.html('Apply');

            refreshScreen();

            box.find(':input').removeAttr('readonly', 'readonly');
            box.find('.form-group').css('pointer-events', 'auto');
        }
    }, '.btn-inject-remove');

    // Charging shocker btn
    $('.monitor-ecg-shock-box').find('.btn-charge-shocker').unbind('click').bind('click', function () {
        var energy = $('.shocker-energy').val();
        chargeShocker(energy);
    });

    // Do Shock (with charged shocker)
    $('.monitor-ecg-shock-box').find('.btn-shock').unbind('click').bind('click', (function () {
        doShock();
    }));

    // Showing case info in treatments page
    $('.show-case-info').click(function () {
        var btn = $(this);
        var target = btn.attr('data-page');
        showPage($(target));
        refreshScreen()
    });
});

/**
 * Converts dash separated string to object route to be used in eval
 * @param {string} string
 * @returns {string}
 */
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

/**
 * Takes action on passing in every page
 *
 * @param page
 */
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

/**
 * Passing Start Page (Biography Page)
 */
function passStartStep() {
    $('#start').removeClass('current');
    $('#start-question').addClass('current');
    window.tmpReaction.toStep = $('#start-question').attr('data-step');
    window.tmpReaction.toPage = $('#start-question');
    window.tmpReaction.done = true;
}

/**
 * Passing Start Questions Page (Three Options)
 *
 * @param page
 */
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
                // pageTimeout($('#more-info'), window.timeouts.moreInfo, ventricularTachycardia);
                break;
            case "2":
                var targetPage = $('#laboratory-exams');
                targetPage.addClass('current');

                window.tmpReaction.toStep = targetPage.attr('data-step');
                window.tmpReaction.toPage = targetPage;

                console.log(currentTime())
                // pageTimeout($('#laboratory-exams'), window.timeouts.exams, ventricularTachycardia);
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

/**
 * Hides current visible page and shows the specified
 *
 * @param pageToShow
 */
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

/**
 * Get back specified or possible step
 * @param page
 * @param returningStepIndex
 * @returns {*}
 */
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

/**
 * Timeout page after specific time with specified action
 * @param page
 * @param time
 * @param timeoutAction
 */
function pageTimeout(page, time, timeoutAction) {
    var timeoutName = "timeout" + $.now();

    window.timers[timeoutName] = setTimeout(function () {
        if (page.hasClass('current')) {
            timeoutAction();
        }
    }, time);

    page.attr('timeout', timeoutName);
}

/**
 * Ventricular Tachycardia
 */
function ventricularTachycardia() {
    console.log(currentTime() + ' : ' + 'VTach');
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

/**
 * Refresh values in string
 */
function refreshScreen() {
    $('.preview-bp').html(window.currentData.SBP + '/' + window.currentData.DBP);
    $('.preview-hr').html(window.currentData.HR);
    $('.preview-rr').html(window.currentData.RR);
    $('.preview-spo2').html(window.currentData.SPO2);
    $('.preview-cvp').html(window.currentData.CVP);
    $('.preview-temperature').html(window.currentData.T);
    $('.preview-uop').html(window.currentData.UOP);
    $('.preview-hgb').html(window.currentData.Hgb);
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

/**
 * Run ecg preview animation
 * @param hr
 */
function runECG(hr) {
    stopECG();
    var ecgBox = $('.monitor-ecg-preview-inner');

    var url = ecgBox.css('background-image').match(/^url\("?(.+?)"?\)$/)

    if (url[1]) {
        url = url[1];
        image = new Image();

        // just in case it is not already loaded
        $(image).on('load', function () {
            var periodWidth = (image.width * ecgBox.height()) / image.height / 40;
            runECGPeriod(hr, periodWidth);

            window.timers.ecgMotion = setInterval(function () {
                runECGPeriod(hr, periodWidth)
            }, 60000);
        });

        image.src = url;
    }
}

/**
 * Run animation of one heart rate
 * @param hr
 * @param periodWidth
 */
function runECGPeriod(hr, periodWidth) {
    $('.monitor-ecg-preview-inner').animate({
        'background-position-x': '-=' + (hr * periodWidth) + 'px'
    }, 60000, 'linear');
}

/**
 * Stop ecg animation
 * @param hr
 */
function stopECG(hr) {
    clearInterval(window.timers.ecgMotion);
    $('.monitor-ecg-preview-inner').stop();
    delete window.timers.ecgMotion;
}

/**
 * Charge shocker with specified energy value
 * @param energy
 */
function chargeShocker(energy) {
    console.log('----------------------------------------charge start------------------------------------------');
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
        console.log('----------------------------------------charge finished------------------------------------------');
    });
}

/**
 * Do shock with charged energy of shocker
 */
function doShock() {
    console.log('----------------------------------------doShock------------------------------------------');
    $('.monitor-ecg-preview-inner').removeClass('VTach').removeClass('dead');

    $('.shocker-charger-box').css('opacity', 0);
    $('.shocker-charger-box').find('.progress-bar').css('width', "0").attr('aria-valuenow', 0);

    if (true) { // check if energy is enough
        playSound('shock');
        clearTimeout(window.timers.die);
        delete window.timers.die;

        // window.reactionForceData = {auto: true};
        backStep();
        delete window.reactionForceData;

        $('.monitor-case-management-panel').show();
        $('.monitor-ecg-shock-box').hide();
        $('.btn-charge-shocker').show();
        $('.btn-shock').hide();
        window.shockerCharge = 0;
    }
}

/**
 * Returns current time in format "yy-mm-dd"
 * @returns {string}
 */
function currentTime() {
    var time = new Date();
    return time.getHours() + ":" + time.getMinutes() + ":" + time.getSeconds();
}

/**
 * Dir case
 */
function kill() {
    console.log(currentTime() + ' : ' + 'die');
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

/**
 * Get value of a json value in case of don't get pointer to that
 * @param data
 * @param key
 */
function getValueOf(data, key) {
    var data = JSON.parse(JSON.stringify(data));
    if (typeof key != 'undefined') {
        return data[key];
    }
    return data;
}

/**
 * Play heart sound with hr frequency
 * @param hr
 */
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

/**
 * Stop current playing heart rate sound
 */
function stopHeartSound() {
    if (typeof window.timers.soundHR != 'undefined') {
        clearInterval(window.timers.soundHR);
        delete window.timers.soundHR;
    }
}

/**
 * Play sound with specified id
 * @param id
 */
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

/**
 * Change "window.currentData"
 * @param param1 If param2 is not specidied param1 should be array
 * @param param2
 */
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

/**
 * Returns array of done treatments
 * (Treatments are returning from this function are only treatments that are we didn't get it back)
 * @returns {Array}
 */
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

/**
 * Need to inject FIO2
 */
function needToFIO2() {
    if (!checkFIO2()) {
        setTimeout(function () {
            if (!checkFIO2()) {
                ventricularTachycardia();
            }
        }, window.timeouts.needToFIO2);
    }
}

/**
 * Check if FIO2 in is injected (and didn't got back)
 * @returns {boolean}
 */
function checkFIO2() {
    var FIO2Key = '3-1';
    var done = doneTreatments();
    if ($.inArray(FIO2Key, done) > -1) { // FIO2 done
        return true;
    }
}

/**
 * Correct preview to correct sizes and scroll bars
 */
function correctView() {
    correctBodyHeight();
    correctVitalSingsHeight();
    correctManagementPanelHeight();
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

/**
 * Find and set the best height for column-1
 */
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

/**
 * Find and set the best height for management panel
 */
function correctManagementPanelHeight() {
    var managementPanel = $('.monitor-case-management-panel');
    var siblings = managementPanel.siblings(':visible');
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
    var managementPanelHeight = availableHeight - (
            parseInt(managementPanel.css('margin-top')) +
            parseInt(managementPanel.css('margin-bottom'))
        );

    managementPanel.height(managementPanelHeight);

    // Inside tab-content of the "treatment-modalities" page
    var treatmentsPage = managementPanel.find('#treatment-modalities.page');
    var needToCalculateMore = false;
    if (!treatmentsPage.is(':visible')) {
        needToCalculateMore = true;
    }
    if (treatmentsPage.length) {
        var navTabs = treatmentsPage.find('.nav');
        if (needToCalculateMore) {
            var previousCss = treatmentsPage.attr("style");
            treatmentsPage
                .css({
                    visibility: 'hidden',
                    display: 'block'
                });

        }

        var navTabsHeight = navTabs.outerHeight();

        if (needToCalculateMore) {
            treatmentsPage.attr("style", previousCss ? previousCss : "");
        }

        var tabContentHeight = managementPanelHeight - navTabsHeight - 2;
        treatmentsPage.find('.tab-content').height(tabContentHeight);
    }
}

function resetShocker() {
    console.log(currentTime() + ' : ' + 'resetShocker');
    window.shockerCharge = 0;

    var shocker = $('.monitor-ecg-shock-box');
    var box = shocker.find('.shocker-charger-box');
    var progressBar = box.find('.progress-bar');
    var energyInput = shocker.find('.shocker-energy');

    energyInput.val(energyInput.find('option').first().val());

    progressBar.css('width', 0);
    progressBar.attr('aria-valuenow', 100);

    $('.btn-charge-shocker').show();
    $('.btn-shock').hide()
        .attr('disabled', 'disabled');
}

function showShocker() {
    $('.monitor-ecg-shock-box').show();
}

function turnShockerOff() {
    $('.monitor-ecg-shock-box').hide();
    resetShocker();
}