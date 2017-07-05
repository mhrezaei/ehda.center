<script>
    var bootstrapTooltip = $.fn.tooltip.noConflict(); // return $.fn.tooltip to previously assigned value

    $(document).on({
        click: function (e) {
            return false;
        }
    }, '.dropdown-toggle');
</script>
{!! Html::script ('assets/libs/bootstrap-select/bootstrap-select.min.js') !!}
{!! Html::script ('assets/libs/jquery.form.min.js') !!}
{!! Html::script ('assets/js/forms.js') !!}
{!! Html::script ('assets/libs/jquery-ui/jquery-ui.min.js') !!}
@include('front.frame.datepicker_assets')
<script>
    $.fn.tooltip = bootstrapTooltip; // give $().bootstrapTooltip the Bootstrap functionality
</script>