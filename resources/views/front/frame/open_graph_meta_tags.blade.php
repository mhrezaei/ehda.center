@section('head')
    @if(!isset($metaTags['title']))
        @php $metaTags['title'] = setting()->ask('site_title')->gain() @endphp
    @endif
    @if($metaTags['title'])
        <meta property="og:title" content="{{ $metaTags['title'] or setting()->ask('site_title')->gain() }}"/>
    @endif

    @if(!isset($metaTags['url']))
        @php $metaTags['url'] = request()->url() @endphp
    @endif
    @if($metaTags['url'])
        <meta property="og:url" content="{{ $metaTags['url'] }}"/>
    @endif

    @if(!isset($metaTags['image']))
        @php $metaTags['image'] = setting()->ask('site_logo')->gain() ? url(setting()->ask('site_logo')->gain()) : '' @endphp
    @endif
    @if($metaTags['image'])
        <meta property="og:image" content="{{ $metaTags['image'] }}"/>
    @endif

    @if(!isset($metaTags['description']))
        @php $metaTags['description'] = '' @endphp
    @endif
    @if($metaTags['description'])
        <meta property="og:description" content="{{ $metaTags['description'] }}"/>
    @endif

@append
