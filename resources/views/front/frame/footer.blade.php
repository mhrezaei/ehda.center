<footer class="bg-blue text-white-deep">
    <div class="container">
        <div class="row">
            @include('front.frame.footer_menu')
            @include('front.frame.footer_contact')
            @include('front.frame.useful_links')
        </div>
        <div class="row" style="text-align: center; color: #FFFAAA; font-size: 12px; padding-top: 20px;">
            <a href="https://yasnateam.com" target="_blank" style="color: #FFFAAA;">{{ trans('front.powered_by_yasna') }}</a>
            <img  src="//sstatic1.histats.com/0.gif?3905330&101" alt="site stats" border="0">
        </div>
    </div>
</footer>
@include('front.frame.jui_scripts')