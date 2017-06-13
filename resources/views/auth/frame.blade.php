@section('head')
    {{ Html::style('assets/css/login.min.css') }}
@endsection

@include('front.frame.header')

{{ null, $background = setting()->ask('login_page_background')->in(getLocale())->gain() }}
<body @if($background) style="background-image: url('{{ url($background) }}')" @endif>
@yield('body')

@yield('endOfBody')
</body>