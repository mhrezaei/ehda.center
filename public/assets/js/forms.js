String.prototype.replaceAll = function (search, replacement) {
    var target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};

$(document).ready(function () {
    forms_listener();
});


///////////////////////////////////////////////
// form validation for:
//    1) required    : .form-required
//    2) number      : .form-number
//    3) persian     : .form-persian
//    4) english     : .form-english
//    5) email       : .form-email
//    6) national    : .form-national
//    7) mobile      : .form-mobile
//    8) phone       : .form-phone
//    9) password    : .form-password => for check verify password field rename that to password id + '2'
//    10) datepicker : .form-datepicker => get timestamp with your input id + 'Extra'
//    11) select     : .form-select
//	  12) checkbox   : .form-checkbox
//	  12) checkbox   : .form-buttonset => will be converted to jquery-ui buttonset
//
// Option => add this attr to form element
//    1) no-validation="1"    :     submit form without javascript validation
//    1) no-ajax="1"          :     submit form with javascript validation without ajax method
///////////////////////////////////////////////

function forms_listener() {
    // javascript forms....
    $("form.js").each(function () {
        var $noAjax = $(this).attr('no-ajax');
        if ($noAjax && $noAjax == 1) {
            $(this).submit();
        }
        else {
            $(this).removeClass('js');
            $(this).ajaxForm({
                dataType: 'json',
                beforeSubmit: forms_validate,
                success: forms_responde,
                error: forms_error
            });
            $('.form-default').focus();
        }
    });

    $(".form-default").each(function () {
        $(this).removeClass('form-default');
        $(this).focus();
    })

    // automatic direction...
    $(".atr").each(function () {
        $(this).removeClass('atl');
        $(this).on("keyup", function () {
            forms_autoDirection(this);
        });
    });

    $(".form-numberFormat").each(function () {
        $(this).removeClass('form-numberFormat');
        $(this).on("keyup", function () {
            forms_numberFormat(this);
        });
        forms_numberFormat(this);
    });

    $(".form-timeFormat").each(function () {
        $(this).removeClass('form-timeFormat');
        $(this).on("keyup", function () {
            forms_timeFormat(this);
        });
        forms_timeFormat(this);
    });
    $(".form-cardFormat").each(function () {
        $(this).removeClass('form-cardFormat');
        $(this).on("keyup", function () {
            forms_cardFormat(this);
        });
        forms_cardFormat(this);
    });

    $("textarea.form-autoSize").each(function () {
        $(this).removeClass('form-autoSize').autogrow();
    });

    $(".persian").each(function () {
        $(this).removeClass('persian');
        $(this).html(forms_pd($(this).html()));
    });

    var juiDataPrefix = {
        datapicker: 'datepicker',
        buttonset: 'buttonset',
    };

    $(".form-datepicker").each(function () {
        var options = $(this).dataStartsWith(juiDataPrefix.datapicker);
        $(this).removeClass('form-datepicker');
        $(this).datepicker(options);
    });

    $(".datepicker").each(function () {
        $(this).removeClass('datepicker');
        var folan = new MHR.persianCalendar($(this).attr('id'),
            {extraInputID: $(this).attr('id') + "_extra", extraInputFormat: "YYYY/MM/DD"}
        );
    });

    $(".form-buttonset").each(function () {
        // var options = $(this).dataStartsWith(juiDataPrefix.buttonset);
        $(this).buttonset();
    });

    $("input.js").each(function () {
        $(this).removeClass('js');
        setTimeout($(this).val(), $(this).attr('data-delay'));
    });

    if ($('.selectpicker').length) {
        $('.selectpicker').selectpicker();
    }
    setTimeout("forms_listener()", 5);
}

function forms_validate(formData, jqForm, options) {

    //Variables...
    var $formId = jqForm.attr('id');
    var $errors = 0;
    var $errors_msg = new Array;
    var $errors_el = new Array;
    var $feed = "#" + $formId + " .form-feed";
    $('#' + $formId + ' button').prop('disabled', true);
    //@TODO: hadi add optional validate

    //Form Feed...
    $($feed).removeClass('alert-success').removeClass('alert-danger').html($($feed + "-wait").html()).slideDown();

    //Bypass...
    var stop = $('#' + $formId).attr('no-validation');
    if (stop && stop == 1) {
        return true;
    }

    $('#' + $formId + ' :input').each(function () {
        if (!$(this).prop('disabled')) { // Doesn't validate and submit value of disabled input
            var $val = $(this).val();
            var $name = $(this).attr('name');
            var $err = $(this).attr('error-value');
            var $err_el = $.inArray($name, $errors_el);
            if ($err_el < 0) {
                forms_markError(this, "reset");
            }

            var $required = $(this).hasClass('form-required');
            var $number = $(this).hasClass('form-number');
            var $persian = $(this).hasClass('form-persian');
            var $english = $(this).hasClass('form-english');
            var $email = $(this).hasClass('form-email');
            var $national = $(this).hasClass('form-national');
            var $mobile = $(this).hasClass('form-mobile');
            var $phone = $(this).hasClass('form-phone');
            var $password = $(this).hasClass('form-password');
            var $datepicker = $(this).hasClass('form-datepicker');
            var $select = $(this).hasClass('form-select');
            var $selectpicker = $(this).hasClass('form-selectpicker');
            var $checkbox = $(this).hasClass('form-checkbox');
            var $radio = $(this).hasClass('form-radio');

            if ($required && $err_el < 0) {
                if (forms_errorIfEmpty(this)) {
                    if ($err && $err.length) {
                        $errors_msg.push($err);
                    }
                    if ($errors < 1) $(this).focus();
                    $errors++;
                    $errors_el.push($name);
                    $err_el = $.inArray($name, $errors_el);
                }
            }

            if ($number && $err_el < 0) {
                if ($(this).val().length > 0) {
                    if (forms_errorIfNotNumber(this)) {
                        if ($err && $err.length) {
                            $errors_msg.push($err);
                        }
                        if ($errors < 1) $(this).focus();
                        $errors++;
                        $errors_el.push($name);
                        $err_el = $.inArray($name, $errors_el);
                    }
                }
            }

            if ($persian && $err_el < 0) {
                if ($(this).val().length > 0) {
                    if (forms_errorIfLang(this, 'fa')) {
                        if ($err && $err.length) {
                            $errors_msg.push($err);
                        }
                        if ($errors < 1) $(this).focus();
                        $errors++;
                        $errors_el.push($name);
                        $err_el = $.inArray($name, $errors_el);
                    }
                }
            }

            if ($english && $err_el < 0) {
                if ($(this).val().length > 0) {
                    if (forms_errorIfLang(this, 'en')) {
                        if ($err && $err.length) {
                            $errors_msg.push($err);
                        }
                        if ($errors < 1) $(this).focus();
                        $errors++;
                        $errors_el.push($name);
                        $err_el = $.inArray($name, $errors_el);
                    }
                }
            }

            if ($email && $err_el < 0) {
                if ($(this).val().length > 0) {
                    if (forms_errorIfNotEmail(this)) {
                        if ($err && $err.length) {
                            $errors_msg.push($err);
                        }
                        if ($errors < 1) $(this).focus();
                        $errors++;
                        $errors_el.push($name);
                        $err_el = $.inArray($name, $errors_el);
                    }
                }
            }

            if ($national && $err_el < 0) {
                if ($(this).val().length > 0) {
                    if (forms_errorIfNotNationalCode(this)) {
                        if ($err && $err.length) {
                            $errors_msg.push($err);
                        }
                        if ($errors < 1) $(this).focus();
                        $errors++;
                        $errors_el.push($name);
                        $err_el = $.inArray($name, $errors_el);
                    }
                }
            }

            if ($mobile && $err_el < 0) {
                if ($(this).val().length > 0) {
                    if (forms_errorIfNotMobile(this)) {
                        if ($err && $err.length) {
                            $errors_msg.push($err);
                        }
                        if ($errors < 1) $(this).focus();
                        $errors++;
                        $errors_el.push($name);
                        $err_el = $.inArray($name, $errors_el);
                    }
                }
            }

            if ($phone && $err_el < 0) {
                if ($(this).val().length > 0) {
                    if (forms_errorIfNotPhone(this)) {
                        if ($err && $err.length) {
                            $errors_msg.push($err);
                        }
                        if ($errors < 1) $(this).focus();
                        $errors++;
                        $errors_el.push($name);
                        $err_el = $.inArray($name, $errors_el);
                    }
                }
            }

            if ($password && $err_el < 0) {
                if ($(this).val().length > 0) {
                    if (forms_errorIfNotVerifyPassWord(this)) {
                        if ($err && $err.length) {
                            $errors_msg.push($err);
                        }
                        if ($errors < 1) $(this).focus();
                        $errors++;
                        $errors_el.push($name);
                        $err_el = $.inArray($name, $errors_el);
                    }
                }
            }

            if ($datepicker && $err_el < 0) {
                if ($(this).val().length > 0) {
                    if (forms_errorIfNotDatePicker(this)) {
                        if ($err && $err.length) {
                            $errors_msg.push($err);
                        }
                        if ($errors < 1) $(this).focus();
                        $errors++;
                        $errors_el.push($name);
                        $err_el = $.inArray($name, $errors_el);
                    }
                }
            }

            if ($radio && $err_el < 0) {
                if ($(this).hasClass('form-required')) {
                    if (forms_errorIfNotCheckedRadio(this)) {
                        if ($err && $err.length) {
                            $errors_msg.push($err);
                        }
                        if ($errors < 1) $(this).focus();
                        $errors++;
                        $errors_el.push($name);
                        $err_el = $.inArray($name, $errors_el);
                    }
                }
            }

            if ($select && $err_el < 0) {
                if ($(this).hasClass('form-required')) {
                    if (forms_errorIfNotSelect(this)) {
                        if ($err && $err.length) {
                            $errors_msg.push($err);
                        }
                        if ($errors < 1) $(this).focus();
                        $errors++;
                        $errors_el.push($name);
                        $err_el = $.inArray($name, $errors_el);
                    }
                }
            }

            if ($selectpicker && $err_el < 0) {
                if ($(this).hasClass('form-required') && $val < 1) {
                    forms_markError($(this), "error");
                    if ($err && $err.length) {
                        $errors_msg.push($err);
                    }
                    if ($errors < 1) $(this).focus();
                    $errors++;
                    $errors_el.push($name);
                    $err_el = $.inArray($name, $errors_el);
                }
            }

            if ($checkbox && $err_el < 0) {
                if (!$(this).is(':checked')) {
                    if (forms_errorIfNotPhone(this)) {
                        if ($err && $err.length) {
                            $errors_msg.push($err);
                        }
                        if ($errors < 1) $(this).focus();
                        $errors++;
                        $errors_el.push($name);
                        $err_el = $.inArray($name, $errors_el);
                    }
                }
            }
        }
    });


    //TODO: Taha i need an value for set it to default for validation, my default value is 0
    if (typeof window[$formId + "_validate"] == 'function') {
        var validate = window[$formId + "_validate"](formData, jqForm, options);
        if (validate != 0) {
            $errors_msg.push(validate);
            $errors++;
        }
    }

    if ($errors > 0) {
        $('#' + $formId + ' button').prop('disabled', false);
        if ($errors_msg.length) {
            var $m = '<ul>';
            for (var i = 0; i < $errors_msg.length; i++) {
                $m += '<li>' + $errors_msg[i] + '</li>';
            }
            $m += '<ul>';
            $($feed).addClass('alert-danger').html($m);
        }
        else {
            $($feed).addClass('alert-danger').html($($feed + "-error").html());
        }
        return false;
    }
    else {
        return true;
    }

}

function forms_error(jqXhr, textStatus, errorThrown, $form) {
    // IMPORTANT NOTE: $formSelector contains nothing! That means forms_errror() cannot identify
    // which form it is and merely shows the errors on all available feeds!

    //Variables...
    var $formId = $form.attr('id');
    var $formSelector = "";
    var $feedSelector = $formSelector + " .form-feed";
    $('#' + $formId + ' button').prop('disabled', false);

//      if( jqXhr.status === 500 ) { //@TODO: Supposed to refresh if _token is wrong. but refreshes in all server errros
//            errorsHtml  = $($feedSelector+'-error').html()      ;
//            setTimeout(function() {window.location.reload()},1000);
//      }
    if (jqXhr.status === 422) {
        $errors = jqXhr.responseJSON;

        errorsHtml = '<ul>';
        $.each($errors, function (key, value) {
            errorsHtml += '<li>' + value[0] + '</li>'; //showing only the first error.
        });
        errorsHtml += '</ul>';
    }
    else {
        errorsHtml = $($feedSelector + '-error').html();
    }

    $($feedSelector).addClass("alert-danger").html(forms_digit_fa(errorsHtml));

}

function forms_responde(data, statusText, xhr, $form) {
    var formSelector = "#" + $form.attr('id');
    var $feedSelector = formSelector + " .form-feed";
    $(formSelector + ' button').prop('disabled', false);

    if (data.ok == '1') {
        var cl = "alert-success";
        var me = data.message;
        if (!me) me = $($feedSelector + '-ok').html();
    } else {
        var cl = "alert-danger";
        var me = data.message;
//            formEffect_markError(data.fields);
//            $(data.fields).focus();
    }
    if (data.feed_class && data.feed_class != 'no') {
        var cl = data.feed_class;
    }

    $($feedSelector).addClass(cl).html(me).show();

    //after effects...
    if (data.refresh == 1) forms_delaiedPageRefresh(1);
    if (data.modalClose == 1 && $(".modal").length)
        setTimeout(function () {
            $(".modal").modal('hide');
        }, 1000);
    if (data.redirect) setTimeout(function () {
        window.location = data.redirect;
    }, data.redirectTime);
    if (data.updater) allForms_updater(data.updater);
    if (data.form_reset) $(formSelector).trigger('reset');
    if (data.feed_timeout) {
        setTimeout(function () {
            $($feedSelector).hide();
        }, data.feed_timeout);
    }
    if (data.callback) setTimeout(data.callback, data.redirectTime);

    return;

}

function forms_reset($selector, $defaultInput) {
    // Form Values...
    $counter = 0;
    $($selector + " input , " + $selector + " textarea ").each(function () {
        if ($(this).attr('type') != 'hidden') {
            $counter++;
            $(this).val('');
            forms_markError(this, 'reset');

            if ($counter == 1 && !$defaultInput) $defaultInput = $(this).attr('name');
        }
    });

    //Feed Area...
    $($selector + " .form-feed").hide();

    //Set Focus...
    setTimeout(function () {
        $($selector + " [name=" + $defaultInput + "]").focus();
    }, 200);

}

function forms_errorIfEmpty(selector) {
    var max = $(selector).attr('maxlength');
    var min = $(selector).attr('minlength');
    if (!$(selector).val() || $(selector).val() == "0") {
        forms_markError(selector, "error");
        return 1;
    }
    else {
        if (max && min) {
            if ($(selector).val().length > max || $(selector).val().length < min) {
                forms_markError(selector, "error");
                return 1;
            }
            else {
                forms_markError(selector, "success");
                return 0;
            }
        }
        else if (max) {
            if ($(selector).val().length > max) {
                forms_markError(selector, "error");
                return 1;
            }
            else {
                forms_markError(selector, "success");
                return 0;
            }
        }
        else if (min) {
            if ($(selector).val().length < min) {
                forms_markError(selector, "error");
                return 1;
            }
            else {
                forms_markError(selector, "success");
                return 0;
            }
        } else {
            forms_markError(selector, "success");
            return 0;
        }
    }
}

function forms_errorIfNotEmail(selector) {
    var email = $(selector).val();
    var filter = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

    if (!filter.test(email)) {
        forms_markError(selector, "error");
        return 1;
    }
    else {
        forms_markError(selector, "success");
        return 0;
    }
}

function forms_errorIfNotNationalCode(selector) {
    if (!forms_national_code(forms_digit_en($(selector).val()))) {
        forms_markError(selector, "error");
        return 1;
    }
    else {
        forms_markError(selector, "success");
        return 0;
    }
}

function forms_errorIfNotNumber(selector) {
    var mixed_var = forms_digit_en($(selector).val());
    var whitespace = " \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000";
    var max = $(selector).attr('maxlength');
    var min = $(selector).attr('minlength');
    if ((typeof mixed_var === 'number' || (typeof mixed_var === 'string' && whitespace.indexOf(mixed_var.slice(-1)) === -
            1)) && mixed_var !== '' && !isNaN(mixed_var)) {
        if (max && min) {
            if (mixed_var.length > max || mixed_var.length < min) {
                forms_markError(selector, "error");
                return 1;
            }
            else {
                forms_markError(selector, "success");
                return 0;
            }
        }
        else if (max) {
            if (mixed_var.length > max) {
                forms_markError(selector, "error");
                return 1;
            }
            else {
                forms_markError(selector, "success");
                return 0;
            }
        }
        else if (min) {
            if (mixed_var.length < min) {
                forms_markError(selector, "error");
                return 1;
            }
            else {
                forms_markError(selector, "success");
                return 0;
            }
        }
    }
    else {
        forms_markError(selector, "error");
        return 1;
    }
}

function forms_errorIfNotMobile(selector) {
    var mixed_var = forms_digit_en($(selector).val());
    var whitespace = " \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000";
    var max = $(selector).attr('maxlength');
    var min = $(selector).attr('minlength');
    if ((typeof mixed_var === 'number' || (typeof mixed_var === 'string' && whitespace.indexOf(mixed_var.slice(-1)) === -
            1)) && mixed_var !== '' && !isNaN(mixed_var) && mixed_var[0] == 0 && mixed_var[1] == 9) {
        if (max && min) {
            if (mixed_var.length > max || mixed_var.length < min) {
                forms_markError(selector, "error");
                return 1;
            }
            else {
                forms_markError(selector, "success");
                return 0;
            }
        }
        else if (max) {
            if (mixed_var.length > max) {
                forms_markError(selector, "error");
                return 1;
            }
            else {
                forms_markError(selector, "success");
                return 0;
            }
        }
        else if (min) {
            if (mixed_var.length < min) {
                forms_markError(selector, "error");
                return 1;
            }
            else {
                forms_markError(selector, "success");
                return 0;
            }
        }
    }
    else {
        forms_markError(selector, "error");
        return 1;
    }
}

function forms_errorIfNotPhone(selector) {
    var mixed_var = forms_digit_en($(selector).val());
    var whitespace = " \n\r\t\f\x0b\xa0\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200a\u200b\u2028\u2029\u3000";
    var max = $(selector).attr('maxlength');
    var min = $(selector).attr('minlength');
    if ((typeof mixed_var === 'number' || (typeof mixed_var === 'string' && whitespace.indexOf(mixed_var.slice(-1)) === -
            1)) && mixed_var !== '' && !isNaN(mixed_var) && mixed_var[0] == 0 && mixed_var[1] != 9) {
        if (max && min) {
            if (mixed_var.length > max || mixed_var.length < min) {
                forms_markError(selector, "error");
                return 1;
            }
            else {
                forms_markError(selector, "success");
                return 0;
            }
        }
        else if (max) {
            if (mixed_var.length > max) {
                forms_markError(selector, "error");
                return 1;
            }
            else {
                forms_markError(selector, "success");
                return 0;
            }
        }
        else if (min) {
            if (mixed_var.length < min) {
                forms_markError(selector, "error");
                return 1;
            }
            else {
                forms_markError(selector, "success");
                return 0;
            }
        }
    }
    else {
        forms_markError(selector, "error");
        return 1;
    }
}

function forms_errorIfNotVerifyPassWord(selector) {
    var max = $(selector).attr('maxlength');
    var min = $(selector).attr('minlength');
    var id = $(selector).attr('id');
    var verify = '#' + id + '2';

    if ($(selector).val() == $(verify).val()) {
        if (max && min) {
            if ($(selector).val().length > max || $(selector).val().length < min) {
                forms_markError(selector, "error");
                forms_markError(verify, "error");
                return 1;
            }
            else {
                forms_markError(selector, "success");
                forms_markError(verify, "success");
                return 0;
            }
        }
        else if (max) {
            if ($(selector).val().length > max) {
                forms_markError(selector, "error");
                forms_markError(verify, "error");
                return 1;
            }
            else {
                forms_markError(selector, "success");
                forms_markError(verify, "success");
                return 0;
            }
        }
        else if (min) {
            if ($(selector).val().length < min) {
                forms_markError(selector, "error");
                forms_markError(verify, "error");
                return 1;
            }
            else {
                forms_markError(selector, "success");
                forms_markError(verify, "success");
                return 0;
            }
        }
    }
    else {
        forms_markError(selector, "error");
        forms_markError(verify, "error");
        return 1;
    }
}

function forms_errorIfLang(selector, lang) {
    var isPersian = forms_isPersian($(selector).val());
    var max = $(selector).attr('maxlength');
    var min = $(selector).attr('minlength');

    if (isPersian && lang != "fa") {
        forms_markError(selector, "error");
        return 1;
    }
    if (!isPersian && lang == "fa") {
        forms_markError(selector, "error");
        return 1;
    }
    else {
        if (max && min) {
            if ($(selector).val().length > max || $(selector).val().length < min) {
                forms_markError(selector, "error");
                return 1;
            }
            else {
                forms_markError(selector, "success");
                return 0;
            }
        }
        else if (max) {
            if ($(selector).val().length > max) {
                forms_markError(selector, "error");
                return 1;
            }
            else {
                forms_markError(selector, "success");
                return 0;
            }
        }
        else if (min) {
            if ($(selector).val().length < min) {
                forms_markError(selector, "error");
                return 1;
            }
            else {
                forms_markError(selector, "success");
                return 0;
            }
        }
    }
}

function forms_errorIfNotSelect(selector) {
    var multi = $(selector).attr('multiple');
    var value = [];

    if (multi) {
        var data = $(selector).val() + '';
        data = data.split(',');
        for (var i = 0; i < data.length; i++) {
            value[i] = data[i];
        }
    }
    else {
        if ($(selector).val() > 0) {
            value[0] = $(selector).val();
        }
    }


    if (!value.length) {
        forms_markError(selector, "error");
        return 1;
    }
    else {
        forms_markError(selector, "success");
        return 0;
    }

}

function forms_errorIfNotDatePicker(selector) {
    var val = $(selector).val();
    if (!val.isValidJalaliDate()) {
        forms_markError(selector, "error");
        return 1;
    }
    else {
        forms_markError(selector, "success");
        return 0;
    }
}

function forms_errorIfNotCheckedRadio(selector) {
    var name = $(selector).attr('name');
    if ($('input[type=radio][name="' + name + '"]:checked').length) {
        forms_markError(selector, "success");
        return false;
    } else {
        forms_markError(selector, "error");
        return true;
    }
}

//======================================================================================

function getLocale() {
    return $('html').attr('lang');
}

function forms_isPersian(string) {
    var p = /[^\u0600-\u06FF]/;
    var count = 0;
    for (var i = 0; i < string.length; i++) {
        if (string[i].match(p)) {
            count++;
        }
    }
    if ((count / string.length) > 0.6) {
        return false;
    }
    else {
        return true;
    }
}

function forms_markError(selector, handle) {
    var formGroup = $(selector).closest('.form-group')
    console.log('formGroup');
    console.log(formGroup);
    console.log(handle);
    if (handle == "success")
        formGroup.addClass("has-success").removeClass('has-error');
    else if (handle == "null" || handle == "reset")
        formGroup.removeClass('has-error').removeClass('has-success');
    else //including "error"
        formGroup.addClass("has-error").removeClass('has-success');//.effect	("shake"	,{times:2},100);
}

function forms_numberFormat(selector) {
    var string = $(selector).val();
    string = forms_digit_en(string.replaceAll(',', ''));

    $(selector).val(forms_digit_fa(addCommas(string)));
}

function forms_timeFormat(selector) {
    var string = $(selector).val();
    string = forms_digit_en(string.replaceAll(':', ''));

    if (string.length > 4) {
        forms_markError(selector, 'danger');
        return;
    }
    else
        forms_markError(selector, 'reset');


    $(selector).val(forms_digit_fa(addTimeSeparator(string)));
}

function forms_cardFormat(selector) {
    var string = $(selector).val();
    string = forms_digit_en(string.replaceAll(' - ', ''));

    if (string.length > 16) {
        forms_markError(selector, 'danger');
        return;
    }
    else
        forms_markError(selector, 'reset');


    $(selector).val(forms_digit_fa(addCardSeparator(string)));
}

function forms_autoDirection(selector) {
    return; //TODO: rectify the lagging problem!
    var $object = $(selector);
    var $persChars = ['ا', 'آ', 'ب', 'پ', 'ت', 'س', 'ج', 'چ', 'ح', 'خ', 'د', 'ذ', 'ر', 'ز', 'ژ', 'س', 'ش', 'ص', 'ض', 'ط', 'ظ', 'ع', 'غ', 'ف', 'ق', 'ک', 'گ', 'ل', 'م', 'ن', 'و', 'ه', 'ی', 'ء', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', ' '];
    var $isPersian = false;


    $object.on("keyup", function () {
        var $string = $object.val();
        var $firstChar = $string.substr(0, 1);
        var $isPersian = false;
        var $i = 0;

        if (!$string) {
            $object.attr("dir", "rtl");
            return;
        }


        for ($i = 0; $i < 45; $i++) {
            if ($persChars[$i] == $firstChar) {
                $isPersian = true;
                break;
            }
        }

        if ($isPersian) {
            $object.attr("dir", "rtl");
        }
        else
            $object.attr("dir", "ltr");

    });


}

function forms_delaiedPageRefresh(time) {
    if (time < 1000) time = time * 1000;

    setTimeout(function () {
        location.reload();
    }, time);
}

function forms_pd($string) {
    if (!$string) return;//safety!

    $string = $string.replaceAll(/1/g, "۱");
    $string = $string.replaceAll(/2/g, "۲");
    $string = $string.replaceAll(/3/g, "۳");
    $string = $string.replaceAll(/4/g, "۴");
    $string = $string.replaceAll(/5/g, "۵");
    $string = $string.replaceAll(/6/g, "۶");
    $string = $string.replaceAll(/7/g, "۷");
    $string = $string.replaceAll(/8/g, "۸");
    $string = $string.replaceAll(/9/g, "۹");
    $string = $string.replaceAll(/0/g, "۰");

    return $string;
}

function forms_digit_en(perDigit) {
    var newValue = "";
    for (var i = 0; i < perDigit.length; i++) {
        var ch = perDigit.charCodeAt(i);
        if (ch >= 1776 && ch <= 1785) // For Persian digits.
        {
            var newChar = ch - 1728;
            newValue = newValue + String.fromCharCode(newChar);
        }
        else if (ch >= 1632 && ch <= 1641) // For Arabic & Unix digits.
        {
            var newChar = ch - 1584;
            newValue = newValue + String.fromCharCode(newChar);
        }
        else
            newValue = newValue + String.fromCharCode(ch);
    }
    return newValue;
}

function pd(enDigit) {
    return forms_digit_fa(enDigit);
}

function ed(faDigit) {
    return forms_digit_en(faDigit);
}

function ad(digists) {
    if (getLocale() == 'fa') {
        return pd(digists);
    }
    return ed(digists);
}

function ad(string) {
    if ($.inArray(getLocale(), ['fa', 'ar']) > -1) {
        return pd(string);
    }
    return ed(string);
}

function forms_digit_fa(enDigit) {
    return forms_pd(enDigit);

    var newValue = "";
    for (var i = 0; i < enDigit.length; i++) {
        var ch = enDigit.charCodeAt(i);
        if (ch >= 48 && ch <= 57) {
            var newChar = ch + 1584;
            newValue = newValue + String.fromCharCode(newChar);
        }
        else {
            newValue = newValue + String.fromCharCode(ch);
        }
    }
    return newValue;
}

function forms_national_code(code) {

    if (code.length == 10 && !isNaN(code)) {
        var code = code.split("");
        var err;
        for (var i = 0; i < code.length; i++) {
            if (code[0] > code[i] || code[0] < code[i]) {
                err = 1;
                break;
            }
            else {
                err = 2;
            }
        }

        if (err == 1) {
            var valid = 0;
            var jumper = 10;
            for (var i = 0; i <= 8; i++) {
                valid += code[i] * jumper;
                --jumper;
            }
            valid = valid % 11;
            if (valid >= 0 && valid < 2) {
                if (valid == code['9']) {
                    return true;
                }
                else {
                    return false;
                }
            }
            else {
                valid = 11 - valid;
                if (valid == code['9']) {
                    return true;
                }
                else {
                    return false;
                }
            }
        }
        else {
            return false;
        }
    }
    else {
        return false;
    }
}


function addCommas(nStr) {

    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}
function addTimeSeparator(nStr) {

    nStr += '';
//	x = nStr.split('.');
//	x1 = x[0];
//	x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{2})/;
    while (rgx.test(nStr)) {
        nStr = nStr.replace(rgx, '$1' + ':' + '$2');
    }
    return nStr;
}
function addCardSeparator(nStr) {

    nStr += '';
//	x = nStr.split('.');
//	x1 = x[0];
//	x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d{4})(\d+)/;
    while (rgx.test(nStr)) {
        nStr = nStr.replace(rgx, '$1' + ' - ' + '$2');
    }
    return nStr;
}

function forms_log($thing) {
    console.log($thing);
}


(function ($) {
    /**
     * Auto-growing textareas; technique ripped from Facebook
     *
     *
     * http://github.com/jaz303/jquery-grab-bag/tree/master/javascripts/jquery.autogrow-textarea.js
     */
    $.fn.autogrow = function (options) {
        return this.filter('textarea').each(function () {
            var self = this;
            var $self = $(self);
            var minHeight = $self.height();
            var noFlickerPad = $self.hasClass('autogrow-short') ? 0 : parseInt($self.css('lineHeight')) || 0;
            var settings = $.extend({
                preGrowCallback: null,
                postGrowCallback: null
            }, options);

            var shadow = $('<div></div>').css({
                position: 'absolute',
                top: -10000,
                left: -10000,
                width: $self.width(),
                fontSize: $self.css('fontSize'),
                fontFamily: $self.css('fontFamily'),
                fontWeight: $self.css('fontWeight'),
                lineHeight: $self.css('lineHeight'),
                resize: 'none',
                'word-wrap': 'break-word'
            }).appendTo(document.body);

            var update = function (event) {
                var times = function (string, number) {
                    for (var i = 0, r = ''; i < number; i++) r += string;
                    return r;
                };

                var val = self.value.replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/\n$/, '<br/>&#xa0;')
                    .replace(/\n/g, '<br/>')
                    .replace(/ {2,}/g, function (space) {
                        return times('&#xa0;', space.length - 1) + ' '
                    });

                // Did enter get pressed?  Resize in this keydown event so that the flicker doesn't occur.
                if (event && event.data && event.data.event === 'keydown' && event.keyCode === 13) {
                    val += '<br />';
                }

                shadow.css('width', $self.width());
                shadow.html(val + (noFlickerPad === 0 ? '...' : '')); // Append '...' to resize pre-emptively.

                var newHeight = Math.max(shadow.height() + noFlickerPad, minHeight);
                if (settings.preGrowCallback != null) {
                    newHeight = settings.preGrowCallback($self, shadow, newHeight, minHeight);
                }

                $self.height(newHeight);

                if (settings.postGrowCallback != null) {
                    settings.postGrowCallback($self);
                }
            }

            $self.change(update).keyup(update).keydown({event: 'keydown'}, update);
            $(window).resize(update);

            update();
        });
    };
})(jQuery);

