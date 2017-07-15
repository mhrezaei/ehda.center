$('.stars circle').click(function () {
    hideActiveStar();
    $(this).addClass('active');
    var circleIndex = $(this).parent().find('circle').index(this);

    var angelsNo = angels.length;
    var rand = Math.floor(Math.random() * (angelsNo - 1 + 1)) + 1;
    var angel = angels[rand];

    showStar(circleIndex, angel.name, angel.picture_url, angel.donate_time);
});
$(document).on('click', function (event) {
    if ($('.user-card').length && !$(event.target).is('circle') && !$(event.target).parents('.user-card').length && !$(event.target).is('.user-card')) hideActiveStar();
});
var notFoundAlert = $('#alertNotFound');

$('.search-angel').on('submit', function (event) {
    event.preventDefault();
    $(this).addClass('loading');
    $('.search-angel').prepend(notFoundAlert).find('.alert').slideUp();

    var angels_name = $('#angels_name').val();
    var tok = $('input[name=_token]').val();

    if (angels_name.length > 3) {
        window.clearTimeout(angles_slide);
        $.ajax({
            type: "POST",
            url: "angels/find",
            cache: false,
            dataType: "json",
            data: {
                angel_name: angels_name,
                _token: tok,
            }
        }).done(function (data) {
            $('.search-angel').removeClass('loading');
            if (data.status === false) {
                $('.search-angel').prepend(notFoundAlert).find('.alert').slideDown();
            } else if (data.status === true) {
                var rand = Math.floor(Math.random() * (19 - 1 + 1)) + 1;
                var circle = $('.circle' + rand);
                circle.addClass('active');
                var circleIndex = circle.parent().find('circle').index(circle);

                var angelsIds = jQuery.map(angels, function(n, i){
                    return n.id;
                });
                var foundedIndexes = [];

                $.each(data.angels, function (index, angel) {
                    var itemIndex = $.inArray(angel.id, angelsIds);
                    if(itemIndex > -1) {
                        foundedIndexes.push(itemIndex);
                    } else {
                        foundedIndexes.push(angels.length);
                        angels.push(angel);
                    }
                });

                window.reservedAngels = foundedIndexes; // set queue to be shown in the future
                random_angles(angels);

                // showStar(circleIndex, Data.name, Data.picture_url, Data.donate_time);
                // $("html, body").animate({scrollTop: $('.user-card').offset().top - $('.user-card').outerHeight() - $('.main-menu').outerHeight()});
            } else {
                alert('خطایی رخ داده است. دوباره سعی کنید.');
            }
        });
    }
    else {
        alert('نام اهدا کننده را وارد نمایید.');
        $('.search-angel').removeClass('loading');
    }
    //Do your ajax
    //success: {
    //                 showStar(circleIndex, name, imageSrc);
    //               `$("html, body").animate({ scrollTop: $('.user-card').offset().top -  $('.user-card').outerHeight() - $('.main-menu').outerHeight()});
    //               }
    //fail: $('.search-angel').prepend(notFoundAlert).find('.alert').slideDown();
    //always: $('.search-angel').removeClass('loading');
});
/**
 * Created by Mohammad Hadi on 12/18/2016.
 */
