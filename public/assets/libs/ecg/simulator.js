/**
 * Created by Yasna-PC1 on 19/07/2017.
 */

window.caseId = 1;
window.reactions = [];
window.timers = {};

$(document).ready(function () {
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

    $('form.treatment-form').find(':input').change(function () {
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

    $('form.treatment-form').submit(function (event) {
        event.preventDefault();
        var form = $(this);
        var equations = window.caseData.calculations;
        var treatment = form.attr('data-treatment');
        var data = form.serializeObject();
        var toDo = eval('equations' + dash2brace(treatment));
        var doneActions = [];

        $.each(toDo, function (num, tasks) {
            // var fieldName = treatment + '-' + num;

            $.each(tasks, function (val, targets) {
                $.each(targets, function (targetName, equation) {
                    $.each(data, function (fieldName, fieldValue) {
                        equation = equation.replace('{{' + fieldName + '}}', fieldValue);
                    });

                    doneActions.push({
                        target: targetName,
                        equation: equation
                    });

                    equation = equation.replace('{{orig}}', window.currentData[targetName]);

                    window.currentData[targetName] = eval(equation);
                });
            });
        });

        console.log(doneActions);
        window.reactions.push(doneActions);
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

        $.each(caseData.defaultData.exams, function (tableTitle, tableData) {
            $.each(tableData, function (key, data) {
                var targetClass = ['case', tableTitle, key].join(' ').title2kebab();
                $('.' + targetClass).html(data);
            });
        });
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

                pageTimeout($('#more-info'), 2 * 60 * 1000, ventricularTachycardia);
                break;
            case "2":
                $('#laboratory-exams').addClass('current');

                window.tmpReaction.toStep = $('#laboratory-exams').attr('data-step');
                window.tmpReaction.toPage = $('#laboratory-exams');

                pageTimeout($('#laboratory-exams'), 2 * 60 * 1000, ventricularTachycardia);
                break;
            case "3":
                $('#treatment-modalities').addClass('current');

                window.tmpReaction.toStep = $('#treatment-modalities').attr('data-step');
                window.tmpReaction.toPage = $('#treatment-modalities');
                break;
        }

        window.tmpReaction.done = true;
    }
}

function backStep(page) {
    var lastStep = window.reactions.last();

    var timeoutName = page.attr('timeout');
    if (timeoutName) {
        clearTimeout(window.timers[timeoutName]);
    }

    var newReaction = {
        fromStep: lastStep.toStep,
        fromPage: lastStep.toPage,
        toStep: lastStep.fromStep,
        toPage: lastStep.fromPage,
        forward: false,
    };

    newReaction.fromPage.removeClass('current');
    newReaction.toPage.addClass('current');

    window.reactions.push(newReaction);
}

function pageTimeout(page, time, timeoutAction) {
    var timeoutName = "timeout" + $.now();
    console.log(page)
    console.log(time / 1000)

    window.timers[timeoutName] = setTimeout(function () {
        if (page.hasClass('current')) {
            timeoutAction();
        }
    }, time);

    page.attr('timeout', timeoutName);
}

function ventricularTachycardia() {
    alert('VTACH!!!!!!!!!!!!!!!!!!!!!!!!!!!!');
}

function refreshScreen() {
}