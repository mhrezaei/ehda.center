<footer class="bg-primary">
    <div class="container">
        <div class="row">
            @include('front.frame.footer_menu')
            @include('front.frame.footer_contact')
        </div>
        <div class="row" style="text-align: center; color: #FFFAAA; font-size: 12px; padding-top: 20px;">
            <a href="https://yasnateam.com" target="_blank" style="color: #FFFAAA;">{{ trans('front.powered_by_yasna') }}</a>
        </div>
    </div>
</footer>
@include('front.frame.jui_scripts')
