var acoordionIcons = {
    header: "ui-icon-plusthick",
    activeHeader: "ui-icon-minusthick"
};

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

$.fn.collapse = function () {
    var target = $(this);
    var targetId = target.attr('id');
    if (targetId) {
        var clickable = $('[data-toggle=collapse][data-target=#' + targetId + '], [data-toggle=collapse][href=#' + targetId + ']');
        if (clickable.length) {
            clickable.click(function () {
                target.slideToggle();
            })
        }
    }
};

$.fn.updateContent = function (callback) {
    var container = $(this);
    var url = container.attr('data-url');
    if (url) {
        $.ajax({
            url: url,
            success: function (result) {
                container.html(result);
                if (typeof callback == 'function') {
                    callback();
                }
            }
        });
    }
};

$.fn.scrollToView = function (extra, duration) {
    var item = $(this);

    if (!$.isNumeric(duration)) {
        duration = 1000;
    }

    if (!$.isNumeric(extra)) {
        extra = 0;
    }

    $('html, body').animate({
        scrollTop: item.offset().top + extra
    }, duration);
};

/**
 * Returns all element containing self element if match "selector"
 * @param {string} selector
 * @returns jQuery
 */
$.fn.findFromThis = function (selector) {
    var fountElements = $(this).find(selector);
    if ($(this).is(selector)) {
        fountElements = $.merge($(this), fountElements);
    }
    return fountElements;
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

function getHashUrl(url) {
    if (url) {
        var hashIndex = url.indexOf('#');
        var hashString = "";
        if (hashIndex != -1) {
            hashString = url.substring(hashIndex + 1);
        }
        return hashString;
    }

    return decodeURIComponent(window.location.hash.substr(1));
}

function setHashUrl(hashString, url) {
    if (url) {
        var hashIndex = url.indexOf('#');
        if (hashIndex == -1) {
            url = url + '#' + hashString;
        } else {
            url = url.substring(0, hashIndex + 1) + hashString;
        }
        return url;
    }

    window.location.hash = hashString;
}

function getPageUrl() {
    return window.location.href.replace(getHashUrl(), '').replace('#', '');
}

function getUrlParameterByName(name, url) {
    if (!url) url = window.location.href;

    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

function removeUrlParameterByName(key, url) {
    if (!url) url = window.location.href;

    var hashIndex = url.indexOf('#');
    var hashString = '';
    if (hashIndex != -1) {
        hashString = url.substring(hashIndex + 1);
        url = url.substring(0, (hashIndex != -1 ? hashIndex : url.length));
    }

    var rtn = url.split("?")[0],
        param,
        params_arr = [],
        queryString = (url.indexOf("?") !== -1) ? url.split("?")[1] : "";

    if (queryString !== "") {
        params_arr = queryString.split("&");
        for (var i = params_arr.length - 1; i >= 0; i -= 1) {
            param = params_arr[i].split("=")[0];
            if (param === key) {
                params_arr.splice(i, 1);
            }
        }

        if (params_arr.length) {
            rtn = rtn + "?" + params_arr.join("&");
        }
    }

    if (hashString) {
        rtn = rtn + '#' + hashString;
    }

    return rtn;
}

function ksort(obj) {
    if (obj) {
        var sorted = {};
        var keys = [];

        for (key in obj) {
            if (obj.hasOwnProperty(key)) {
                keys.push(key);
            }
        }
    }

    keys.sort();

    for (i = 0; i < keys.length; i++) {
        sorted[keys[i]] = obj[keys[i]];
    }

    return sorted;
}

function loadingDialog(parameter, dialog) {
    if (isDefined(dialog)) {
        if (!(dialog instanceof HtmlElement) && (typeof dialog == 'string')) {
            dialog = $(dialog);
        }
    } else {
        dialog = $('#loading-dialog');
    }

    if (dialog.length) {
        if ($.inArray(parameter, ['hide', false, 0]) > -1) {
            dialog.hide();
        } else {
            dialog.show();
        }
    }
}

function openUrl(url, target) {
    if (!isDefined(target)) {
        target = '_blank';
    }
    var win = window.open(url, target);
    if (win) {
        win.focus();
    } else {
        alert('Please allow popups for this website');
    }
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

    if ($('.yt-accordion').length) {
        $('.yt-accordion').accordion({
            collapsible: true,
            autoHeight: true,
            icons: acoordionIcons,

            beforeActivate: function (event, ui) {
                // The accordion believes a panel is being opened
                if (ui.newHeader[0]) {
                    var currHeader = ui.newHeader;
                    var currContent = currHeader.next('.ui-accordion-content');
                    // The accordion believes a panel is being closed
                } else {
                    var currHeader = ui.oldHeader;
                    var currContent = currHeader.next('.ui-accordion-content');
                }
                // Since we've changed the default behavior, this detects the actual status
                var isPanelSelected = currHeader.attr('aria-selected') == 'true';

                // Toggle the panel's header
                currHeader.toggleClass('ui-corner-all', isPanelSelected)
                    .toggleClass('ui-accordion-header-active', !isPanelSelected)
                    .attr('aria-selected', ((!isPanelSelected).toString()));

                // Toggle the panel's icon
                currHeader.find('.ui-icon')
                    .toggleClass(acoordionIcons.header, isPanelSelected)
                    .toggleClass(acoordionIcons.activeHeader, !isPanelSelected);

                // Toggle the panel's content
                currContent.toggleClass('accordion-content-active', !isPanelSelected);
                if (isPanelSelected) {
                    currContent.slideUp();
                } else {
                    currContent.slideDown();
                }

                return false; // Cancel the default action
            }
        });
    }

    $('.yt-lazy-load').each(function () {
        var container = $(this);
        var url = container.attr('data-url');

        if (url) {
            if (container.hasClass('auto-height')) {
                container.css('height', 'auto');
            }

            $.ajax({
                url: url,
                type: container.attr('data-method') ? container.attr('data-method') : 'GET',
                success: function (result) {
                    container.html(result);
                }
            });


            container.on({
                click: function (e) {
                    e.preventDefault();

                    url = $(this).attr('href');
                    if (url) {
                        $.ajax({
                            url: url,
                            success: function (result) {
                                container.html(result);
                                container.removeClass('loading');
                            },
                            beforeSend: function () {
                                container.addClass('loading');
                            }
                        });
                    }
                }
            }, '.pagination li a');
        }
    })

    if ($('.yt-accordion').length) {
        $(".yt-accordion").find('.yt-accordion-header.default-active').each(function () {
            var item = $(this);
            var accordion = item.closest('.yt-accordion');

            if (!item.hasClass('ui-accordion-header-active')) {
                var index = item.index('.default-active') ? item.index('.default-active') : false;
                var panel = accordion.find('.yt-accordion-panel').eq(index);
                accordion.accordion("option", "active", index);
                if (panel.hasClass('auto-height')) {
                    panel.css('height', 'auto');
                }
            }
        });
    }

    $('.like-submit-button').click(function () {
        var form = $(this).closest('form');
        if (form.length) {
            if (!form.find(':input[type=submit]').length) {
                form.append($('<input type="submit"/>').css('display', 'none'));
            }

            form.find(':input[type=submit]').click();
        }
    });

    $('.collapse').each(function () {
        $(this).collapse();
    });
});

