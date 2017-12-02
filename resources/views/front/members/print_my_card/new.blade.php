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
        @if($type == 'full')
            img{
                width: 21cm;
                height: 29.7cm;
            }
        @endif
    </style>

    <title>{{ trans('forms.button.card_print') }}</title>
</head>
<body onload="window.print();">
{{--@TODO: server address should set dynamic --}}
<img src="{{ 'https://ehda.center/card/show_card_new/' . $type . '/' . hashid_encrypt($user->id, 'ehda_card_' . $type) }}" alt="{{ trans('forms.button.card_print') }}">

</body>
</html>