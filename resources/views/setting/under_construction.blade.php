<html>
<head>
    <title>{{ setting()->ask('site_title')->gain() }}</title>
    <style>
        html {
            background: url({{ url(getSetting('under_construction_image')) }}) no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }
    </style>
    <body>

</body>
</head>
</html>