<script>

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


    var juiDataPrefix = {
        datapicker: 'datepicker',
    };

    $(document).ready(function () {
        $.datepicker.setDefaults({
            dateFormat: 'yy/mm/dd'
        });

        $('.datepicker-input').each(function () {
            var options = $(this).dataStartsWith(juiDataPrefix.datapicker);
            $(this).datepicker(options);
        });
    });
</script>