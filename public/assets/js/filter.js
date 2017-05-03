/**
 * Created by EmiTis Yousefi on 30/04/2017.
 */

$(document).ready(function () {
    initialFilter();
});

var filterableAttributes = [];

function initialFilter() {
    if ($('.filters-panel').length) {

        $('.filters-panel').find('.filter-text').each(function (index) {
            var box = $(this);
            var identifier = box.attr('data-identifier');

            filterableAttributes.push(identifier);

            box.on({
                keyup: function () {
                    filterWithText($(this).val(), identifier);
                }
            }, 'input[type=text]');

            box.find('input[type=text]').change();
        });

        $('.filters-panel').find('.filter-checkbox').each(function (index) {
            var box = $(this);
            var identifier = box.attr('data-identifier');

            filterableAttributes.push(identifier);

            box.on({
                change: function () {
                    filterWithCheckBox(identifier, true);
                }
            }, 'input[type=checkbox]');

            filterWithCheckBox(identifier, true);
        });

        $('.filters-panel').find('.filter-switch-checkbox').each(function (index) {
            var box = $(this);
            var identifier = box.attr('data-identifier');

            filterableAttributes.push(identifier);

            box.on({
                change: function () {
                    filterWithSwitchCheckBox($(this).is(':checked'), identifier);
                }
            }, 'input[type=checkbox]');

            box.find('input[type=checkbox]').change();
        });

        $('.filters-panel').find('.filter-slider').each(function (index) {
            var box = $(this);
            var identifier = box.attr('data-identifier');
            var container = box.find('.slider-container');
            var minLabel = container.find('.min-label');
            var maxLabel = container.find('.max-label');

            var min = parseInt(container.attr('data-min'));
            var max = parseInt(container.attr('data-max'));

            filterableAttributes.push(identifier);

            minLabel.html(forms_pd(nummber_format(min)));
            maxLabel.html(forms_pd(nummber_format(max)));
            container.find('.slider-self').slider({
                range: true,
                min: min,
                max: max,
                values: [min, max],
                slide: function (event, ui) {
                    minLabel.html(forms_pd(nummber_format(ui.values[0])));
                    maxLabel.html(forms_pd(nummber_format(ui.values[1])));
                    filterWithRange(ui.values[0], ui.values[1], identifier);
                }
            });
        });
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

    $('.filterable').each(function () {
        var value = $(this).attr('data-' + identifier);
        var handleClass = identifier + '-false';
        var beVisible = false;


        if (arrayValue) {
            value = value.split(',');
        } else {
            value = [value];
        }

        if ($(checked).filter(value).length) {
            beVisible = true;
        }

        if (beVisible) {
            $(this).removeClass(handleClass);
        } else {
            $(this).addClass(handleClass);
        }
    });

    doFilter();
}

function filterWithSwitchCheckBox(checked, identifier) {

    $('.filterable').each(function () {
        var value = $(this).attr('data-' + identifier);
        var handleClass = identifier + '-false';

        if (checked) {
            if (value == checked) {
                $(this).removeClass(handleClass);
            } else {
                $(this).addClass(handleClass);
            }
        } else {
            $(this).removeClass(handleClass);
        }
    });

    doFilter();
}

function filterWithText(needle, identifier) {

    $('.filterable').each(function () {
        var value = $(this).attr('data-' + identifier);
        var handleClass = identifier + '-false';

        if (value.smartSearch(needle)) {
            $(this).removeClass(handleClass);
        } else {
            $(this).addClass(handleClass);
        }
    });

    doFilter();
}

function filterWithRange(min, max, identifier) {

    $('.filterable').each(function () {
        var value = parseInt($(this).attr('data-' + identifier));
        var handleClass = identifier + '-false';

        if (value >= min && value <= max) {
            $(this).removeClass(handleClass);
        } else {
            $(this).addClass(handleClass);
        }
    });

    doFilter();
}

function doFilter() {
    $('.filterable').each(function () {
        var item = $(this);
        var beVisible = true;

        $.each(filterableAttributes, function (index, attr) {
            if (item.hasClass(attr + '-false')) {
                beVisible = false;
                return false;
            }
        });

        if (beVisible) {
            item.show();
        } else {
            item.hide();
        }
    })
}