<!DOCTYPE html>
<html lang="{{ getLocale() }}">
@include('front.ecg.frame.head')
<body>

@yield('body')
@include('front.ecg.frame.scripts')
@yield('end-of-body')
</body>
</html>