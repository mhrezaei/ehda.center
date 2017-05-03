/**
 * Created by EmiTis Yousefi on 30/04/2017.
 */


$(document).ready(function () {
    initialFilter();

    $(window).on('hashchange', function (e) {
        initialFilter(true)
    });
});

var ajaxDelay = 1; // in seconds
var ajaxTimer = new Timer();
var filterableAttributes = [];
var filterData = {};
var filterUrl = '';

function initialFilter(modify) {
    var filterPanel = $('.filters-panel');

    if (!isDefined(modify) || !modify) {
        modify = false;
    }

    if (filterPanel.length) {
        var urlAttr = filterPanel.attr('data-filter-url');

        if (typeof urlAttr !== typeof undefined) {
            var currentFilterData = decryptHash(getHashUrl());

            filterUrl = urlAttr;

            /**
             * generate text filters
             */
            filterPanel.find('.filter-text').each(function (index) {
                var box = $(this);
                var identifier = box.attr('data-identifier');

                filterableAttributes.push(identifier);

                if (!modify) {
                    box.on({
                        change: function (event) {
                            filterWithText($(this).val(), identifier);
                            if (event.originalEvent) {
                                doFilter();
                            }
                        }
                    }, 'input[type=text]');
                }

                if (isDefined(currentFilterData['text']) && isDefined(currentFilterData['text'][identifier])) {
                    box.find('input[type=text]').val(currentFilterData['text'][identifier]).change();
                }

            });

            /**
             * generate range filters
             */
            filterPanel.find('.filter-slider').each(function (index) {
                var box = $(this);
                var identifier = box.attr('data-identifier');
                var container = box.find('.slider-container');
                var minLabel = container.find('.min-label');
                var maxLabel = container.find('.max-label');

                var min = parseInt(container.attr('data-min'));
                var max = parseInt(container.attr('data-max'));

                filterableAttributes.push(identifier);

                if (isDefined(currentFilterData['range']) &&
                    isDefined(currentFilterData['range'][identifier]) &&
                    isDefined(currentFilterData['range'][identifier]['min']) &&
                    isDefined(currentFilterData['range'][identifier]['max'])) {
                    var values = [currentFilterData['range'][identifier]['min'], currentFilterData['range'][identifier]['max']];
                } else {
                    var values = [min, max];
                }

                var sliderEl = container.find('.slider-self');
                if (modify) {
                    sliderEl.slider('values', values);
                } else {
                    sliderEl.slider({
                        range: true,
                        min: min,
                        max: max,
                        values: [min, max],
                        change: function (event, ui) {
                            minLabel.html(forms_pd(nummber_format(ui.values[0])));
                            maxLabel.html(forms_pd(nummber_format(ui.values[1])));
                            filterWithRange(ui.values[0], ui.values[1], identifier);
                            if (event.originalEvent) {
                                doFilter();
                            }
                        }
                    });
                    sliderEl.slider('values', values);
                }

                minLabel.html(forms_pd(nummber_format(values[0])));
                maxLabel.html(forms_pd(nummber_format(values[1])));
            });

            /**
             * generate checkbox group filters
             */
            filterPanel.find('.filter-checkbox').each(function (index) {
                var box = $(this);
                var identifier = box.attr('data-identifier');

                filterableAttributes.push(identifier);

                if (!modify) {
                    box.on({
                        change: function (event) {
                            filterWithCheckBox(identifier);
                            if (event.originalEvent) {
                                doFilter();
                            }
                        }
                    }, 'input[type=checkbox]');
                }


                if (isDefined(currentFilterData['checkbox']) &&
                    isDefined(currentFilterData['checkbox'][identifier]) &&
                    $.isArray(currentFilterData['checkbox'][identifier])
                ) {
                    box.find('input[type=checkbox]').each(function () {
                        var cat = $(this).val();
                        if ($.inArray(cat, currentFilterData['checkbox'][identifier]) > -1) {
                            $(this).prop('checked', true);
                        } else {
                            $(this).prop('checked', false);
                        }
                    }).change();
                } else {
                    box.find('input[type=checkbox]').prop('checked', true).change();
                }
            });

            /**
             * generate switchKey filters (checkbox based)
             */
            filterPanel.find('.filter-switch-checkbox').each(function (index) {
                var box = $(this);
                var identifier = box.attr('data-identifier');

                filterableAttributes.push(identifier);

                if (!modify) {
                    box.on({
                        change: function (event) {
                            filterWithSwitchCheckBox($(this).is(':checked'), identifier);
                            if (event.originalEvent) {
                                doFilter();
                            }
                        }
                    }, 'input[type=checkbox]');
                }


                if (isDefined(currentFilterData['switchKey']) &&
                    isDefined(currentFilterData['switchKey'][identifier]) &&
                    currentFilterData['switchKey'][identifier]) {
                    box.find('input[type=checkbox]').prop('checked', true);
                } else {
                    box.find('input[type=checkbox]').prop('checked', false).change();
                }
            });

            if (Object.keys(currentFilterData).length) {
                doFilter();
            }

        } else {
            console.warn('Filter URL is not defined!');
        }
    }
}

function filterWithText(needle, identifier) {
    if (typeof filterData.text == 'undefined') {
        filterData.text = {};
    }

    filterData.text[identifier] = needle;
}

function filterWithRange(min, max, identifier) {
    if (typeof filterData.range == 'undefined') {
        filterData.range = {};
    }

    filterData.range[identifier] = {
        min: min,
        max: max
    };
}

function filterWithCheckBox(identifier, arrayValue) {
    if (typeof arrayValue == 'undefined') {
        arrayValue = false;
    }
    var sisters = $('input[type=checkbox][id^=' + identifier + ']');
    var checked = [];
    sisters.each(function () {
        if ($(this).is(':checked')) {
            checked.push($(this).val());
        }
    });

    if (typeof filterData.checkbox == 'undefined') {
        filterData.checkbox = {};
    }

    if (checked.length) {
        filterData.checkbox[identifier] = checked;
    } else {
        delete filterData.checkbox[identifier]; // make it undefined
    }
}

function filterWithSwitchCheckBox(checked, identifier) {

    if (typeof filterData.switchKey == 'undefined') {
        filterData.switchKey = {};
    }

    if (checked) {
        filterData.switchKey[identifier] = checked;
    } else {
        delete filterData.switchKey[identifier];
    }
}

function getHashUrl() {
    return decodeURIComponent(window.location.hash.substr(1));
}

function setHashUrl(hashString) {
    window.location.hash = hashString;
}

function encryptHash(input) {
    var result = '';
    $.each(input, function (key, group) {
        if (Object.keys(group).length) {
            result += '!';

            var parts = [];
            $.each(group, function (identifier, data) {
                var groupPrefix = identifier + '_';

                switch (typeof data) {
                    case 'boolean':
                        parts.push(groupPrefix + ((data) ? 1 : 0));
                        break;
                    case 'string':
                        parts.push(groupPrefix + data);
                        break;
                    case 'object':
                        if ($.isArray(data)) {
                            $.each(data, function (i, value) {
                                if (typeof value == 'boolean') {
                                    parts.push(groupPrefix + ((value) ? 1 : 0));
                                } else {
                                    parts.push(groupPrefix + value);
                                }
                            });
                        } else {
                            $.each(data, function (index, value) {
                                var tmpPrefix = groupPrefix + index + '_';
                                if (typeof value == 'boolean') {
                                    parts.push(tmpPrefix + ((value) ? 1 : 0));
                                } else {
                                    parts.push(tmpPrefix + value);
                                }
                            });
                        }
                        break;
                }
            });
            result += parts.join('/');

            result += '?' + key;
        }
    });

    result += ((result) ? '!' : '');

    return result;
}

function decryptHash(hash) {
    var hashArray = {};

    hash = hash.splitNotEmpty('!');

    $.each(hash, function (i, field) {
        field = field.splitNotEmpty('?');
        if (field.length == 2) {
            hashArray[field[1]] = field[0].splitNotEmpty('/');
            hashArray[field[1]] = prefixToIndex('_', hashArray[field[1]]);
        }
    });

    return hashArray;
}

function getFilterResult() {
    var hash = getHashUrl();
    var targetEl = $('.product-list');

    $.ajax({
        type: 'POST',
        url: filterUrl,
        data: {
            hash: hash,
            _token: window.Laravel.csrfToken
        },
        beforeSend: function () {
            targetEl.addClass('loading');
        },
        success: function (result) {
            targetEl.replaceWith($(result))
            targetEl.removeClass('loading');
        }
    });
}

function doFilter() {
    setHashUrl(encryptHash(filterData));
    ajaxTimer.delay(getFilterResult, ajaxDelay);
}