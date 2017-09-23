function farsiDigits(txt) {
    for (var i = 0; i < 10; i++)
        txt = (txt + '').replace(new RegExp(i + '', 'g'), String.fromCharCode((i + '').charCodeAt(0) + (0x06f0 - 0x0030)));
    return txt;
}

function twoDigits(number) {
    return number = ('0' + number).slice(-2);
}

function selectCity() {
    var ci = '<option value="0">انتخاب کنید...</option>';
    var selectSt = $('#cbRegisterState');
    var selectCi = $('#cbRegisterCity');
    selectCi.html(ci);
    var city = window['city'];
    if (selectSt.val() > 0 && selectSt.val() < 32) {
        city = city[selectSt.val()];
        for (var c in city) {
            ci += '<option value="' + city[c].id + '">' + city[c].name + '</option>';
        }
        selectCi.html(ci);
    }
}

function selectEditCity(id) {
    var ci = '<option value="0">انتخاب کنید...</option>';
    var selectSt = $('#cbRegisterState');
    var selectCi = $('#cbRegisterCity');
    selectCi.html(ci);
    var city = window['city'];
    //console.log(city);
    if (selectSt.val() > 0 && selectSt.val() < 32) {
        city = city[selectSt.val()];
        for (var c in city) {
            if (city[c].id == id) {
                ci += '<option value="' + city[c].id + '" selected="selected">' + city[c].name + '</option>';
            }
            else {
                ci += '<option value="' + city[c].id + '">' + city[c].name + '</option>';
            }
        }
        selectCi.html(ci);
    }
}


var openMenu = null;
$(document).on('click', function (event) {
    if (openMenu && $(event.target) != openMenu && !$(event.target).parents('.has-child').length) {
        openMenu.slideUp(500, function () {
            openMenu.parents('.has-child').first().removeClass('active');
            openMenu = null;
        })
    }
});
$(document).on('click', '.has-child', function (event) {
    var elm = null;
    let target = $(event.target);
    if (target.is('.has-child')) {
        elm = target;
    } else {
        if(target.closest('a').length) {
            target = target.closest('a');
        }
    }
    if (target.is('a') && target.parent().is('.has-child')) {
        elm = target.parent();
    }
    if (elm) {
        event.preventDefault();
        elm.addClass('active');
        elm.siblings().removeClass('active').find('>ul').slideUp();
        elm.find('>ul').stop(true, false).slideToggle({
            done: function (e) {
                if ($(e.elem).css('display') != 'none') {
                    openMenu = $(e.elem);
                    openMenu.parents('.has-child').first().addClass('active');
                } else {
                    openMenu.parents('.has-child').first().removeClass('active');
                    openMenu = null;
                }
            }
        });
    }
});


$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
    $(window).scroll(function () {
        var winTop = $(window).scrollTop();
        var mainMenuHeight = $('.main-menu').outerHeight();
        if (winTop >= $('.top-bar').outerHeight()) {
            $('.main-menu').height(mainMenuHeight);
            $('body').css('margin-top', mainMenuHeight);
            $("body").addClass("sticky-header");
        } else {
            $("body").removeClass("sticky-header");
            $('body').css('margin-top', 0);
            $('.main-menu').css('height', 'auto');
        }
        if (winTop >= 300) {
            if ($('.go-to-top').length == 0) {
                $('body').append('<a href="#" class="go-to-top"><i class="icon icon-down rotate-180"></i></a>');
            }
            $('.go-to-top').stop(true, false).fadeIn();
        } else {
            $('.go-to-top').stop(true, false).fadeOut(200, function () {
                $(this).remove()
            })
        }
    });
    $(document).on('click', '.go-to-top', function (event) {
        event.preventDefault();
        $("html, body").animate({scrollTop: 0});
    })
    $('#current-members h3').fitText(2.5, {maxFontSize: '50px'});
    $('#home-notes h3').fitText(2.5, {maxFontSize: '30px'});
    $('body').css('margin-bottom', $('body>footer').outerHeight());
    $(window).resize(function () {
        $('.timers .timer span').css('line-height', $('.timers .circle').first().height() + 'px');
        $('body').css('margin-bottom', $('body>footer').outerHeight());
    });
    $('#chRegisterAll').on('change', function () {
        if (this.checked) {
            $(this).parent().parent().find('[type="checkbox"]').prop('checked', true);
        } else {
            $(this).parent().parent().find('[type="checkbox"]').prop('checked', false);
        }
    })
    $('.body-items [type="checkbox"]:not(#chRegisterAll)').on('change', function () {
        if ($('.body-items [type="checkbox"]:not(#chRegisterAll)').length == $('.body-items [type="checkbox"]:not(#chRegisterAll):checked').length) {
            $('#chRegisterAll').prop('checked', true);
        } else {
            $('#chRegisterAll').prop('checked', false);
        }
    });
    $(window).trigger('resize');
    $(window).on('resize', function () {
        $(window).trigger('scroll');
        if ($(window).width() > 999) {
            $('#menu-tree').removeAttr('style');
        }
    });

    $('.toggle-menu').click(function () {
        let menuEl = $('#menu-tree');

        if($('.main-menu')[0].style.height != 'auto') {
            $('.main-menu').css('height', 'auto');
        }
        
        if (menuEl.is(':visible')) {
            menuEl.slideUp();
        } else {
            menuEl.slideDown();
        }
    });

    $('.globe-list-btn').click(function (e) {
        e.preventDefault();
        let listEl = $('.globe-list');
        if (listEl.is(':visible')) {
            listEl.slideUp(function () {
                listEl.removeAttr('style');
            });
        } else {
            listEl.slideDown();
        }
    });

    $('.globe-list-btn').blur(function () {
        let listEl = $('.globe-list');
        if (listEl.is(':visible')) {
            listEl.slideUp(function () {
                listEl.removeAttr('style');
            });
        }
    });
});
(function ($) {
    $.fn.fitText = function (kompressor, options) {
        // Setup options
        var compressor = kompressor || 1,
            settings = $.extend({
                'minFontSize': Number.NEGATIVE_INFINITY,
                'maxFontSize': Number.POSITIVE_INFINITY
            }, options);
        return this.each(function () {
            // Store the object
            var $this = $(this);
            // Resizer() resizes items based on the object width divided by the compressor * 10
            var resizer = function () {
                $this.css('font-size', Math.max(Math.min($this.width() / (compressor * 10), parseFloat(settings.maxFontSize)), parseFloat(settings.minFontSize)));
            };
            // Call once to set.
            resizer();
            // Call on resize. Opera debounces their resize by default.
            $(window).on('resize.fittext orientationchange.fittext', resizer);
        });
    };
})(jQuery);