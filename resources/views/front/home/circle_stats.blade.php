@section('endOfBody')
    <script>
        String.prototype.replaceAll = function (search, replacement) {
            var target = this;
            return target.replace(new RegExp(search, 'g'), replacement);
        };

        function getLocale() {
            return $('html').attr('lang');
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
    </script>
    {!! Html::script ('assets/js/circle-progress.min.js') !!}
@append
<div class="timers container">
    {!! $deadlinesHTML !!}
</div>