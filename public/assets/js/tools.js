/**
 * Created by Yasna-PC1 on 22/07/2017.
 */

/**
 * Converts a camelCase string to a kebab-case one
 * @returns {string}
 */
String.prototype.camel2kebab = function () {
    return this.replace(/([a-z])([A-Z])/g, '$1-$2').toLowerCase();
};

/**
 * Converts string from kebab-case format to camelCase format
 * @returns {string}
 */
String.prototype.kebab2camel = function () {
    return this.replace(/-([a-z])/g, function (g) {
        return g[1].toUpperCase();
    });
};

/**
 * Converts string from Title Case to camelCase
 * @returns {string|*}
 */
String.prototype.title2camel = function () {
    var string = this.toLowerCase();
    var parts = string.split(" ");

    parts.slice(1).forEach(function (part, index) {
        parts[index + 1] = part.ucfirst();
    });

    return parts.join('');
};

/**
 * Converts string from Title Case to kebab-case
 * @returns {string}
 */
String.prototype.title2kebab = function () {
    return this.title2camel().camel2kebab();
};

/**
 * Makes a string ucfirst
 * @returns {string}
 */
String.prototype.ucfirst = function () {
    return this.replace(/(?:^|\s)\w/g, function (match) {
        return match.toUpperCase();
    });
};

/**
 * Makes a string lcfirst
 * @returns {string}
 */
String.prototype.lcfirst = function () {
    return this.replace(/(?:^|\s)\w/g, function (match) {
        return match.toLowerCase();
    });
};

/**
 * Guess file name from its url
 * @param extension
 * @returns {string}
 */
String.prototype.filename = function (extension) {
    var s = this.replace(/\\/g, '/');
    s = s.substring(s.lastIndexOf('/') + 1);
    return extension ? s.replace(/[?#].+$/, '') : s.split('.')[0];
};

/**
 * Replaces all occurrences of "search" with "replacement"
 * @param {string} search
 * @param {string} replacement
 * @returns {string}
 */
String.prototype.replaceAll = function (search, replacement) {
    var target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};

/**
 * Returns last item of an array
 * @returns {*}
 */
Array.prototype.last = function () {
    return this[this.length - 1];
};

/**
 * Returns a column of an object array
 * @param columnName
 * @returns {*}
 */
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

/**
 * Serializes inputs data and makes object
 * @returns {{}}
 */
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
 * Returns all data-attributes starting with "data-prefix" as an object with camelCase names
 * @param prefix
 * @returns {{}}
 */
$.fn.dataStartsWith = function (prefix) {
    prefix = prefix.kebab2camel();
    var result = {};
    var data = $(this).data();

    $.each(data, function (key, val) {
        if (key.startsWith(prefix)) {
            result[key.substr(prefix.length).lcfirst()] = val;
        }
    });

    return result;
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
 * Returns current time in format "yy-mm-dd"
 * @returns {string}
 */
function currentTime() {
    let time = new Date();
    return time.getHours() + ":" + time.getMinutes() + ":" + time.getSeconds();
}

/**
 * Returns parameters received by url in this page
 * @param {string} paramName
 * @returns {null}
 */
function getUrlParam(paramName) {
    let reParam = new RegExp('(?:[\?&]|&)' + paramName + '=([^&]+)', 'i');
    let match = window.location.search.match(reParam);
    return ( match && match.length > 1 ) ? match[1] : null;
}
