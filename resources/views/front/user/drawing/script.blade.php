<script>
    function drawingCode() {
        var $input1 = ed($('#gift-input1').val());
        var $input2 = ed($('#gift-input2').val());
        var $input3 = ed($('#gift-input3').val());
        var $input4 = ed($('#gift-input4').val());
        var tok = $("input[name='_token']").val();
        var load = $('.load').html();
        var result = $('.result-item');

        result.html(load).show();

        if($input1 + $input2 + $input3 + $input4 > 0)
        {
            $.ajax({
                type: "POST",
                url: "{{ url('') }}" + "/drawing/check",
                cache: false,
                dataType: "json",
                data: {
                    code1: $input1, code2: $input2, code3: $input3, code4: $input4,
                    _token: tok,
                }
            }).done(function(Data){
                if(Data.status == 'success')
                {
                    result.text(Data.msg).show();
                    if (Data.login)
                    {
                        setTimeout(function () {
                            window.location = "{{ url_locale('user/drawing') }}";
                        }, 3000);
                    }
                    else
                    {
                        setTimeout(function () {
                            window.location = "{{ url('/register') }}";
                        }, 3000);
                    }
                }
                else
                {
                    result.text(Data.msg).show();
                }
            });
        }
        else
        {
            result.text('{{ trans('front.drawing_check_code_fail') }}').show();
        }

    }
</script>