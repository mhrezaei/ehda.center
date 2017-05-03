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

String.prototype.smartSearch = function (search) {
    source = this;

    source = source.toLowerCase();
    search = search.toLowerCase();
    var searchArray = search.split(" ");
    var found = 0;

    $.each(searchArray, function (index, value) {
        if (source.startsWith(value) ||
            (source.search(" " + value) > -1)) {
            found++;
        }
    });

    if (found == searchArray.length) {
        return true;
    } else {
        return false;
    }
};

String.prototype.reverse = function () {
    return this.split("").reverse().join("");
};

String.prototype.splitNotEmpty = function (delimiter) {
    return this.match(new RegExp("[^" + delimiter + "]+", "gi"));
};

String.prototype.limitedSplit = function (delimiter, limit, notEmpty) {
    if ((typeof notEmpty == typeof undefined) || !notEmpty) {
        var arr = this.split(delimiter);
    } else {
        var arr = this.splitNotEmpty(delimiter);
    }

    if ((typeof limit == typeof undefined) || (arr.length <= limit)) {
        return arr;
    }

    var result = arr.splice(0, (limit - 1));

    result.push(arr.join(delimiter));

    return result;
};

$.smartMerge = function () {
    var allArray = true;
    var checking = arguments;
    $.each(checking, function (i, argument) {
        if (!$.isArray(argument)) {
            allArray = false;
            return false;
        }
    });

    if (allArray) {
        return $.merge.apply(this, checking);
    }

    $.each(checking, function (i, argument) {
        if ($.isArray(argument)) {
            checking[i] = $.extend({}, argument);
        }
    });

    return $.extend.apply(this, checking);
};

function nummber_format(text) {
    if (typeof text == 'number') {
        text = text.toString();
    }

    if (typeof parseInt(text) == 'number') {
        text = text.reverse();
        var parts = text.match(/.{1,3}/g);
        text = parts.join(',');
        text = text.reverse();
        return text;
    }
}

function prefixToIndex(delimiter, haystack) {
    switch (typeof haystack) {
        case 'object':
            var output = {};
            $.each(haystack, function (index, field) {
                var parts = field.limitedSplit(delimiter, 2);
                if (parts.length == 2) {
                    var key = parts[0];
                    var value = parts[1];
                    value = prefixToIndex(delimiter, value);
                    if (isDefined(output[key])) {
                        if (typeof output[key] != 'object') {
                            output[key] = [output[key]];
                        }

                        if (typeof value != 'object') {
                            value = [value];
                        }

                        output[key] = $.smartMerge(output[key], value);
                    } else {
                        output[key] = value;
                    }
                }
            });
            return output;
            break;

        case 'string':
            var parts = haystack.limitedSplit(delimiter, 2);
            if (parts.length == 2) {
                var key = parts[0];
                var value = parts[1];
                value = prefixToIndex(delimiter, value);

                haystack = {};
                haystack[key] = value;

                return haystack;
            }
            break;
    }
    return haystack;
}

function isDefined(input) {
    return !(typeof input == typeof undefined);
}

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

