@section('head')
    {!! Html::style('assets/libs/bootstrap-select/bootstrap-select.min.css') !!}
    <style>
        a.ehda-card {
            display: none;
        }
    </style>
@append

@section('endOfBody')
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

        /**
         * Thing to do while getting to every steps from every directions
         * @param {int} stepNumber
         */
        function goToStep(stepNumber) {
            console.log('goToStep');
            console.log(stepNumber);
            console.log('--------------------------');

            // Hide "feeds"
//            setTimeout(function () {
//                $('#register_form').find('.form-feed').hide();
//            }, 15000);

            // Changing the flag for discovering step of registration
            $('#step-number').val(stepNumber);

            switch (stepNumber) {
                case 1:
                    // Disabling additional input
                    $('#additional-fields').find(':input').attr('disabled', 'disabled');
                    break;
                case 2:
                    // Make the "code_melli" field readonly
                    $('#code_melli').attr('readonly', 'readonly');
                    break;
                case 3:
                    // Showing related buttons
                    $('#form-buttons').hide();
                    $('#last-step-buttons').show();
                    break;
            }
        }

        /**
         * Thing to do while getting to each step from its previous step
         * @param {int} stepNumber
         */
        function upToStep(stepNumber) {
            console.log('upToStep');
            console.log(stepNumber);
            console.log('--------------------------');
            goToStep(stepNumber);

            switch (stepNumber) {
                case 1:
                    break;

                case 2:
                    // Enabling additional input
                    $('#additional-fields').find(':input').removeAttr('disabled');

                    // Show the additional fields for step 2 to the form
                    $('#additional-fields').slideDown();

                    // Refreshing view of buttonsets
                    $(".form-buttonset").each(function () {
                        // var options = $(this).dataStartsWith(juiDataPrefix.buttonset);
                        $(this).buttonset().buttonset('refresh');
                    });

                    // Showing cancel button to get back to step 1
                    $('#cancel-button').show();

                    // Hide "feeds"
                    $('#register_form').find('.form-feed').hide();
                    break;
                case 3:
                    // Making all inputs in form readonly
                    $('#register_form').find(':input').attr('readonly', 'readonly');
                    $('#register_form').find('.form-group').css('pointer-events', 'none');

                    // Hide all buttons
                    $('#form-buttons').hide();
//                    $('#last-step-buttons').hide();
                    break;
            }
        }

        function downToStep(stepNumber) {
            // Hide "feeds"
            $('#register_form').find('.form-feed').hide();

            switch (stepNumber) {
                case 1:
                    // Hide additional fields (the fields related to step 2)
                    $('#additional-fields').slideUp();

                    // Make the "code_melli" field writable
                    $('#code_melli').removeAttr('readonly');

                    // Reset values of additional inputs in
                    $('#additional-fields :input, #name_first, #name_last, #code_melli').each(function () {
                        if ($(this).is(':radio')) {
                            $(this).prop('checked', false);
                        } else {
                            $(this).val('');
                        }

                        $(this).change();
                    });

                    // Hiding cancel button
                    $('#cancel-button').hide();
                    break;
                case 2:
                    // Making all inputs in form writable
                    $('#register_form').find(':input').removeAttr('readonly');
                    $('#register_form').find('.form-group').css('pointer-events', 'auto');

                    // Showing related buttons
                    $('#form-buttons').show();
                    $('#last-step-buttons').hide();
                    break;
            }

            goToStep(stepNumber);
        }

        function downStep() {
            downToStep(parseInt($('#step-number').val()) - 1);
        }

        $(document).ready(function () {
            upToStep(1);
        });
    </script>
@append

<div class="row article">
    <div class="container">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="row border-2 border-blue pt15 pb15 rounded-corners-5">
                        @if(!user()->exists)
                            @include('front.card.card_info.register_card_form')
                        @else
                            <div class="row">
                                <div class="col-xs-12">
                                    <img src="{{ user()->cards('mini') }}">
                                </div>
                                <div class="col-xs-12 text-center">
                                    <a class="btn btn-lightBlue" href="{{ route_locale('user.profile.edit') }}">
                                        {{ trans('front.edit_profile') }}
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-8 col-sm-6 col-xs-12">
                    <div class="row">
                        <div class="col-xs-12 text-justify">
                            <h2 style="margin-top: 0">{{ $post->title }}</h2>
                            {!! $post->text !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>