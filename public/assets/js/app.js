$(document).ready(function(){

    var body_rtl = false;
    if($('body').css('direction') == 'rtl'){
        body_rtl = true;
    }

    /*-----------------------------------------------------------------
     - Dropdown
     -----------------------------------------------------------------*/
    $('.dropdown-toggle').on('click', function(e) {
        e.preventDefault();
        if($(this).next('.menu').hasClass('open')){
            $('.dropdown-toggle').removeClass('active').next('.menu').removeClass('open');
        } else {
            $('.dropdown-toggle').removeClass('active').next('.menu').removeClass('open');
            $(this).addClass('active').next('.menu').addClass('open');
        }

    });
    $(document).on('click', function(e) {
        var target = e.target;
        if (!$(target).is('.dropdown-toggle, .dropdown-toggle *') && !$(target).parents().is('.dropdown-toggle + .menu')) {
            $('.dropdown-toggle').removeClass('active');
            $('.dropdown .menu').removeClass('open');
        }
    });

    /*-----------------------------------------------------------------
    - FAQ
    -----------------------------------------------------------------*/
    $('.faq-item:first-child').addClass('open');
    $('.faq-item a.q').on('click', function (e) {
        e.preventDefault();
        $(this).next('.answer').slideToggle('fast', function () {
            $(this).parent('.faq-item').toggleClass('open');
        });
    });

    /*-----------------------------------------------------------------
    - Home Slider
    -----------------------------------------------------------------*/
    $('#home-slides ul.home-slides').responsiveSlides({
        auto: true,
        pager: true,
    });

    /*-----------------------------------------------------------------
    - Product Gallery
    -----------------------------------------------------------------*/
    $('ul.slides').responsiveSlides({
        auto: false,
        manualControls: "#product-gallery-thumbnails",
    });
    $('.thumbnails').slick({
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        rtl: body_rtl,
        prevArrow: '<a href="#" class="arrow icon-angle-left"></a>',
        nextArrow: '<a href="#" class="arrow icon-angle-right"></a>',
    });

    /*-----------------------------------------------------------------
    - Testimonials
    -----------------------------------------------------------------*/
    $('.testimonials-list').slick({
        autoplay: true,
        centerMode: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        rtl: false,
        arrows: false,
    });


    /*-----------------------------------------------------------------
    - Gift input
    -----------------------------------------------------------------*/
    $("input.gift-input").keydown(function (e) {
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
            (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
            (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
            (e.keyCode >= 35 && e.keyCode <= 39)) {
            return;
        }
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
        $(this).val(persianJs($(this).val()).englishNumber().toString());
    });
    $("input.gift-input").keyup(function(){
        $(this).val(persianJs($(this).val()).englishNumber().toString());
        if (this.value.length >= this.attributes["maxlength"].value) {
            $(this).next('.gift-input').focus();
        }
    });


    /*-----------------------------------------------------------------
     - Modal
     -----------------------------------------------------------------*/
    $('[data-modal]').on('click', function(e) {
        e.preventDefault();
        var target = $(this).attr('data-modal');
        var file_elm = $(this).attr('data-index');
        if (!target.match("^#")) {
            target = '#' + target;
        }
        $('body').addClass('modal-open');
        $('.modal-wrapper').addClass('open');
        $(target).addClass('open').attr('data-file', file_elm);

        $( 'body' ).append( '<div class="overlay"></div>' );
        setTimeout(function(){
            $('.overlay').addClass('open');
            $('.blurable').addClass('open');
        }, 10);
    });
    $(document).on('click', '.modal-wrapper, .close-modal', function(e) {

        if($(e.target).is('.modal *')){
            return;
        }

        e.preventDefault();

        $('body').removeClass('modal-open');
        $('.modal').removeClass('open');
        $('.modal-wrapper').removeClass('open');

        $('.overlay').removeClass('open');
        $('.blurable').removeClass('open');
        setTimeout(function(){
            $('.overlay').remove();
        }, 300);
    });


});