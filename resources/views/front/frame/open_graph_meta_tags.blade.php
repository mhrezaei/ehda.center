<meta property="og:title" content="{{ $title or setting()->ask('site_title')->gain() }}" />
<meta property="og:url" content="{{ $url or request()->url() }}" />
<meta property="og:image" content="{{ $image or url(setting()->ask('site_logo')->gain()) or '' }}" />
<meta property="og:description" content="{{ $description or '' }}" />