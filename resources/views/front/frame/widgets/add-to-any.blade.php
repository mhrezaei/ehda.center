<!-- AddToAny BEGIN -->
<div class="a2a_kit a2a_kit_size_32 a2a_default_style" data-a2a-url="{{ $shareUrl or url()->current() }}"
     data-a2a-title="{{ $shareTitle or setting()->ask('site_title')->gain() }}">
    <a class="a2a_dd mt10" href="https://www.addtoany.com/share"></a>
    <a class="a2a_button_facebook mt10"></a>
    <a class="a2a_button_twitter mt10"></a>
    <a class="a2a_button_google_plus mt10"></a>
    <a class="a2a_button_telegram mt10"></a>
    @include('front.frame.widgets.add-to-any-parts.short-url')
</div>

@section('endOfBody')
    <script>
        var a2a_config = a2a_config || {};
        a2a_config.onclick = 1;
        a2a_config.num_services = 4;
        a2a_config.color_main = "D7E5ED";
        a2a_config.color_border = "AECADB";
        a2a_config.color_link_text = "333333";
        a2a_config.color_link_text_hover = "333333";
    </script>
    <script async src="https://static.addtoany.com/menu/page.js"></script>
@append
<!-- AddToAny END -->