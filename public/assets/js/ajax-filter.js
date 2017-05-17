/**
 * Created by EmiTis Yousefi on 30/04/2017.
 */

var ajaxDelay = 1; // in seconds
var ajaxTimer = new Timer();
var runningXhr = null;
var filterableAttributes = [];
var filterData = {};
var filterUrl = '';

$(document).ready(function () {
    initialFilter();

    $(window).on('hashchange', function (e) {
        e.preventDefault();
        initialFilter(true)
    });

    $('.ajax-sort').change(function () {
        var selected = $(this).find('option:selected');
        if (typeof filterData.sort == 'undefined') {
            filterData.sort = {};
        }
        filterData.sort[selected.attr('data-identifier')] = selected.val();
        modifyUrl();
    });
});

function initialFilter(modify) {
    var filterPanel = $('.filters-panel');
    filterData = {};
    if (!isDefined(modify) || !modify) {
        modify = false;
    }

    if (filterPanel.length) {
        var urlAttr = filterPanel.attr('data-filter-url');

        if (typeof urlAttr !== typeof undefined) {

            var currentFilterData = decryptHash(getHashUrl());

            if (isDefined(currentFilterData.pagination)) {
                filterData.pagination = currentFilterData.pagination;
            }

            if (isDefined(currentFilterData.sort)) {
                filterData.sort = currentFilterData.sort;
            }

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
                        keyup: function (event) {
                            // TODO: minimum characters
                            filterWithText($(this).val(), identifier);
                            if (event.originalEvent) {
                                resetPageNumber();
                                modifyUrl();
                            }
                        }
                    }, 'input[type=text]');
                }

                if (isDefined(currentFilterData['text']) &&
                    isDefined(currentFilterData['text'][identifier]) &&
                    currentFilterData['text'][identifier]
                ) {
                    box.find('input[type=text]').val(currentFilterData['text'][identifier]).keyup();
                } else {
                    box.find('input[type=text]').val('').keyup();
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
                            minLabel.html(ad(nummber_format(ui.values[0])));
                            maxLabel.html(ad(nummber_format(ui.values[1])));
                            filterWithRange(ui.values[0], ui.values[1], identifier, [sliderEl.slider("option", "min"), sliderEl.slider("option", "max")]);
                            if (event.originalEvent) {
                                resetPageNumber();
                                modifyUrl();
                            }
                        }
                    });
                    sliderEl.slider('values', values);
                }

                minLabel.html(ad(nummber_format(values[0])));
                maxLabel.html(ad(nummber_format(values[1])));
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
                                resetPageNumber();
                                modifyUrl();
                            }
                        }
                    }, 'input[type=checkbox]');
                }


                var items = box.find('input[type=checkbox]');

                if (isDefined(currentFilterData['checkbox']) &&
                    isDefined(currentFilterData['checkbox'][identifier])
                ) {
                    items.each(function () {
                        var cat = $(this).val();
                        if (!$.isArray(currentFilterData['checkbox'][identifier])) {
                            currentFilterData['checkbox'][identifier] = [currentFilterData['checkbox'][identifier]];
                        }

                        if ($.inArray(cat, currentFilterData['checkbox'][identifier]) > -1) {
                            $(this).prop('checked', true);
                        } else {
                            $(this).prop('checked', false);
                        }
                    });
                } else {
                    items.prop('checked', true);
                }

                items.last().change();
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
                                resetPageNumber();
                                modifyUrl();
                            }
                        }
                    }, 'input[type=checkbox]');
                }


                if (isDefined(currentFilterData['switchKey']) &&
                    isDefined(currentFilterData['switchKey'][identifier]) &&
                    currentFilterData['switchKey'][identifier]) {
                    box.find('input[type=checkbox]').prop('checked', true).change();
                } else {
                    box.find('input[type=checkbox]').prop('checked', false).change();
                }
            });

            modifyUrl();
            if (modify) {
                doFilter();
            } else {
                doFilter(0);
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

    if (needle) {
        filterData.text[identifier] = needle;
    } else {
        delete filterData.text[identifier];
    }
}

function filterWithRange(min, max, identifier, range) {
    if (typeof filterData.range == 'undefined') {
        filterData.range = {};
    }

    if (!range || !$.isArray(range) || min > range[0] || max < range[1]) {
        filterData.range[identifier] = {
            min: min,
            max: max
        };
    } else {
        delete filterData.range[identifier]; // make it undefined
    }
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

    if (checked.length < sisters.length) {
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

    if (hash) {
        $.each(hash, function (i, field) {
            field = field.splitNotEmpty('?');
            if (field.length == 2) {
                hashArray[field[1]] = field[0].splitNotEmpty('/');
                hashArray[field[1]] = prefixToIndex('_', hashArray[field[1]]);
            }
        });
    }

    return hashArray;
}

function doFilter(delay) {
    var targetEl = $('.result-container');
    targetEl.addClass('loading');
    loadingDialog();
    if (isDefined(delay)) {
        var timeOut = delay;
    } else {
        var timeOut = ajaxDelay;
    }
    ajaxTimer.delay(function () {
        var hash = getHashUrl();

        runningXhr = $.ajax({
            type: 'POST',
            url: filterUrl,
            data: {
                hash: hash,
                _token: window.Laravel.csrfToken
            },
            beforeSend: function () {
                if (runningXhr) {
                    runningXhr.abort();
                    console.warn('Filter request canceled!');
                }
            },
            success: function (result) {
                targetEl.replaceWith($(result));
                $('#category-header').scrollToView(-20);
                modifyPaginationLinks();
            },
            complete: function () {
                runningXhr = null;
                loadingDialog('hide');
            }
        });

    }, timeOut);
}

function modifyUrl(getData) {
    var newHash = encryptHash(ksort(filterData));
    if (newHash != getHashUrl()) {
        setHashUrl(newHash);
    }
}

function modifyPaginationLinks() {
    $('.pagination li:not(.active):not(.disabled) a').each(function () {
        var item = $(this),
            link = item.attr('href');

        var pageN = getUrlParameterByName('page', link);
        if (pageN != null) {
            link = removeUrlParameterByName('page', link);

            var hashString = getHashUrl(link);
            var hashArray = decryptHash(hashString);
            hashArray.pagination = {};
            hashArray.pagination.page = pageN;

            var newHashString = encryptHash(hashArray);
            item.attr('href', setHashUrl(newHashString, link));
        }
    });
}

function resetPageNumber() {
    delete filterData.pagination;
}

function resetFilters() {
    filterData = {};
    modifyUrl();
}