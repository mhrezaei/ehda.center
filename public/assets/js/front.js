/**
 * returns all data-attributes starting with "data-prefix" as an object with camelCase names
 * @param prefix
 * @returns {{}}
 */
$.fn.dataStartsWith = function (prefix) {
    var result = {};
    var data = $(this).data();

    $.each(data, function (key, val) {
        if (key.startsWith(prefix)) {
            result[key.substr(prefix.length).lcfirst()] = val;
        }
    });

    return result;
};

String.prototype.ucfirst = function () {
    return this.replace(/(?:^|\s)\w/g, function (match) {
        return match.toUpperCase();
    });
};

String.prototype.lcfirst = function () {
    return this.replace(/(?:^|\s)\w/g, function (match) {
        return match.toLowerCase();
    });
};

String.prototype.isValidJalaliDate = function (separator) {
    if (typeof separator == 'undefined') { // set "/" as default separator
        separator = '/';
    }
    var regex = new RegExp('^((\\d{4})|(\\d{2}))' + separator + '\\d{1,2}' + separator + '\\d{1,2}$');
    var bits = this.split(separator);
    if (regex.test(this) && bits.length == 3) {
        var d = new JalaliDate(bits[0], bits[1], bits[2]);
        return !!(d && (d.getMonth()) == Number(bits[1]) && d.getDate() == Number(bits[2]));
    } else {
        return false;
    }
};


$(document).ready(function () {

    /**
     * make datepicker for marriage date visible if "married" has been selected form marital radio
     */
    $('input[type=radio][name=marital]').change(function () {
        var field = $('#marriage_date').closest('.field');
        if (field.length) {
            if ($(this).is(':checked')) {
                if ($(this).val() == 2) {
                    field.slideDown();
                } else {
                    field.slideUp();
                }
            }
        }
    }).change();
})