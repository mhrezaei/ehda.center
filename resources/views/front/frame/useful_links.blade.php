<div class="col-xs-12 col-md-4">
    @php
        $links = \App\Providers\PostsServiceProvider::selectPosts([
            'type' => 'useful-links',
            'limit' => 6,
            'random' => true,
        ])->get();
    @endphp
    @if($links and $links->count())
        <h5>{{ trans('front.useful_links') }}</h5>
        @foreach($links as $key => $link)
            @php $link->spreadMeta() @endphp
            @if($key != 0) <br /> @endif
            <a href="{{ $link['link'] }}" target="_blank">{{ $link['title'] }}</a>
        @endforeach
    @endif
</div>
<img id='sizpjzpeoeukesgtsizp' style='cursor:pointer' onclick='window.open("https://logo.samandehi.ir/Verify.aspx?id=97809&p=pfvljyoemcsiobpdpfvl", "Popup","toolbar=no, scrollbars=no, location=no, statusbar=no, menubar=no, resizable=0, width=450, height=630, top=30")' alt='logo-samandehi' src='https://logo.samandehi.ir/logo.aspx?id=97809&p=bsiyyndtaqgwlymabsiy'/>