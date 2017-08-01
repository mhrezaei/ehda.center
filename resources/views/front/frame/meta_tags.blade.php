@section('head')
    @if(isset($metaTags['keywords']) and $metaTags['keywords'] and !is_object($metaTags['keywords']))
        @if(is_array($metaTags['keywords']))
            @php $metaTags['keywords'] = implode(',', $metaTags['keywords']) @endphp
        @endif
        <meta name="keywords" content="{{ $metaTags['keywords'] }}"/>
    @endif

    @if(
            isset($metaTags['title']) and
            $metaTags['title'] and
            !is_object($metaTags['title']) and
            !is_array($metaTags['title'])
        )
        <meta name="title" content="{{ $metaTags['title'] }}"/>
    @endif

    @if(isset(
            $metaTags['description']) and
            $metaTags['description'] and
            !is_object($metaTags['description']) and
            !is_array($metaTags['description'])
        )
        <meta name="description" content="{{ $metaTags['description'] }}"/>
    @endif

    @if(isset($metaTags['robots']) )
        @if($metaTags['robots'] and !is_object($metaTags['robots']))
            @if(is_array($metaTags['robots']))
                @php $metaTags['robots'] = implode(',', $metaTags['robots']) @endphp
            @endif
        @endif
    @else
        @php $metaTags['robots'] = 'index, follow' @endphp
    @endif
    <meta name="robots" content="{{ $metaTags['robots'] }}"/>

@append