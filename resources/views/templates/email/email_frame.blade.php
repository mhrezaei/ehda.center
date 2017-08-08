<html lang="fa">
<head>
    <meta charset="utf-8">
</head>
<body>
@if(isLangRtl())
    @php $direction = 'rtl' @endphp
@else
    @php $direction = 'ltr' @endphp
@endif
<div style="direction: {{ $direction }};text-align:right;font-family:Tahoma;font-size:8pt;background-color:White">
    <div marginwidth="0" marginheight="0"
         style="width:100%;margin:0;padding:0;background-color:#f5f5f5;font-family:tahoma">
        <div style="display:block;min-height:5px;background-color:#32689a"></div>
        <center>
            <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                <tbody>
                <tr>
                    <td align="center" valign="top" style="border-collapse:collapse;color:#525252">
                        <table border="0" cellpadding="0" cellspacing="0" width="700">
                            <tbody>
                            <tr>
                                <td align="center" valign="top" height="20"
                                    style="border-collapse:collapse;color:#525252"></td>
                            </tr>
                            <tr>
                                <td align="center" valign="top" style="border-collapse:collapse;color:#525252">
                                    <table width="100%" border="0">
                                        <tbody>
                                        <tr>
                                            <td width="100%" align="center"
                                                style="border-collapse:collapse;color:#525252">
                                                <table border="0" cellpadding="0" cellspacing="0"
                                                       style="margin-bottom:10px">
                                                    <tbody>
                                                    <tr>
                                                        <td width="641" height="34" align="center"
                                                            style="border-collapse:collapse;color:#525252">
                                                            <a href="#" style="width:80px;min-height:34px;display:block"
                                                               target="_blank">
                                                            </a></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td align="center" height="38" style="border-collapse:collapse;color:#525252"></td>
                            </tr>
                            <tr>
                                <td align="center" valign="top" style="border-collapse:collapse;color:#525252">
                                    <table width="100%" valign="top" border="0" cellpadding="0" cellspacing="0">
                                        <tbody>
                                        <tr>
                                            <td width="100%"
                                                style="direction {{ $direction }};border-collapse:collapse;color:#525252;padding:10px;background-color:rgb(255,255,255);border-color:rgb(221,221,221);border-width:1px;border-radius:5px;border-style:solid;font-size:8pt;padding:15px 40px!important"
                                                align="justify" dir="{{ $direction }}" valign="top">
                                                <table dir="{{ $direction }}" style="direction:{{ $direction }}">
                                                    <tbody>
                                                    <tr>
                                                        <td style="border-collapse:collapse;color:#525252;padding:0px!important;text-align:justify;direction:{{ $direction }};font-family:Tahoma;line-height:20px;font-size:8pt"
                                                            align="right" valign="top"><br>
                                                            <div style="font-size:13px;color: #6AA84F;text-align:justify;font-family:Tahoma">
                                                                @yield('email_content')
                                                            </div>
                                                            <br>

                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" valign="top" height="10"
                                                style="border-collapse:collapse;color:#525252"></td>
                                        </tr>
                                        <tr>
                                            <td align="center" valign="top"
                                                style="border-collapse:collapse;color:#525252">
                                                <table width="100%" valign="top" border="0" cellpadding="0"
                                                       cellspacing="0" dir="{{ $direction }}">
                                                    <tbody>
                                                    <tr>
                                                        <td width="100%"
                                                            style="border-collapse:collapse;color:#525252;padding:10px;background-color:rgb(255,255,255);border-color:rgb(221,221,221);border-width:1px;border-radius:5px;border-style:solid;font-size:8pt;padding:15px 40px!important"
                                                            align="justify" valign="top">
                                                            <p style="line-height:20px;font-family:Tahoma;font-size:8pt">
                                                                {{ trans('front.email_has_been_sent_automatically') }}
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" valign="top" height="10"
                                                            style="border-collapse:collapse;color:#525252"></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="100%"
                                                            style="font-size:8pt;border-collapse:collapse;color:#525252;padding:10px;background-color:rgb(255,255,255);border-color:#e8e8e8;border-width:1px;border-radius:5px;border-style:solid;font-size:8pt;padding:15px 40px!important"
                                                            align="left" valign="top">
                                                            <center>
                                                                <a href="{{ url_locale() }}"
                                                                   style="color:#999;text-decoration:none;font-family:Tahoma;font-size:8pt"
                                                                   target="_blank">
                                                                    {{ trans('front.home') }}
                                                                </a>
                                                                {{--&nbsp; |&nbsp;&nbsp;--}}
                                                                {{--<a href="{{ url_locale('about') }}"--}}
                                                                   {{--style="color:#999;text-decoration:none;font-family:Tahoma;font-size:8pt"--}}
                                                                   {{--target="_blank">--}}
                                                                    {{--{{ trans('front.about') }}--}}
                                                                {{--</a>--}}
                                                                &nbsp; |&nbsp;
                                                                <a href="{{ url_locale('contact') }}"
                                                                   style="color:#999;text-decoration:none;font-family:Tahoma;font-size:8pt"
                                                                   target="_blank">
                                                                    {{ trans('front.contact_us') }}
                                                                </a>
                                                                <a href="{{ route_locale('volunteer.register.step.1.get') }}"
                                                                   style="color:#999;text-decoration:none;font-family:Tahoma;font-size:8pt"
                                                                   target="_blank">
                                                                    {{ trans('front.volunteer_section.singular') }}
                                                                </a>
                                                                <a href="{{ route('register_card') }}"
                                                                   style="color:#999;text-decoration:none;font-family:Tahoma;font-size:8pt"
                                                                   target="_blank">
                                                                    {{ trans('front.organ_donation_card_section.singular') }}
                                                                </a>
                                                            </center>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" valign="top" height="33"
                                                style="border-collapse:collapse;color:#525252">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border-collapse:collapse;color:#525252">

                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" valign="top" height="30"
                                                style="border-collapse:collapse;color:#525252">
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            </tbody>
                        </table>
        </center>
    </div>
</div>
</body>
</html>