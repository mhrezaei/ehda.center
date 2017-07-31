/**
 * Created by Yasna-PC1 on 22/07/2017.
 */

/**
 * Converts a CamelCase string to a kebab-case one
 * @returns {string}
 */

String.prototype.camel2kebab = function () {
    return this.replace(/([a-z])([A-Z])/g, '$1-$2').toLowerCase();
};

String.prototype.title2camel = function () {
    var string = this.toLowerCase();
    var parts = string.split(" ");

    parts.slice(1).forEach(function (part, index) {
        parts[index + 1] = part.ucfirst();
    });

    return parts.join('');
};

String.prototype.title2kebab = function () {
    return this.title2camel().camel2kebab();
};

String.prototype.ucfirst = function () {
    return this.charAt(0).toUpperCase() + this.slice(1);
};

String.prototype.filename = function (extension) {
    var s = this.replace(/\\/g, '/');
    s = s.substring(s.lastIndexOf('/') + 1);
    return extension ? s.replace(/[?#].+$/, '') : s.split('.')[0];
};

Array.prototype.last = function () {
    return this[this.length - 1];
};

Array.prototype.getColumn = function (columnName) {
    return $.map(this, function (val) {
        return val[columnName] ? val[columnName] : null;
    })
};

Object.byString = function (o, s) {
    s = s.replace(/\[(\w+)\]/g, '.$1'); // convert indexes to properties
    s = s.replace(/^\./, '');           // strip a leading dot
    var a = s.split('.');
    for (var i = 0, n = a.length; i < n; ++i) {
        var k = a[i];
        if (k in o) {
            o = o[k];
        } else {
            return;
        }
    }
    return o;
};

$.fn.serializeObject = function () {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function () {
        if (o[this.name]) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

/**
 * Create event listener for show or hide elements
 */
$.each(['show', 'hide'], function (i, ev) {
    var el = $.fn[ev];

    $.fn[ev] = function () {
        el.apply(this, arguments)
        this.trigger(ev);
        return $(this);
    };
});