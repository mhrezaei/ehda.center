<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body{
            width: 100%;
            background: #ffffff !important;
            margin: 0 auto;
            text-align: center;
        }
        img{
            width: 21cm;
            height: 29.7cm;
        }
    </style>

    <title>{{ trans('forms.button.card_print') }}</title>
</head>
<body onload="window.print();">

<img src="{{ url('/card/show_card/full/' . encrypt(Auth::user()->code_melli) . '/print') }}" alt="{{ trans('forms.button.card_print') }}">

</body>
</html>