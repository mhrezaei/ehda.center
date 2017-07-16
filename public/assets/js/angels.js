function hideActiveStar() {
    $('.user-card').fadeOut(400, function () {
        $(this).remove();
        $('.stars circle.active').removeClass('active');
    });
}

function showStar(circleIndex, name, imgSrc, donation_date) {
    hideActiveStar();
    var userCard = $('\
        <div class="user-card" style="display:none;">\
            <img width="100" height="100" class="img-circle angel-img" onload="$(this).fadeIn()">\
            <h6 class="angel-name"></h6>\
            <h6 class="angel-time"></h6>\
          </div>\
      ');
    var card = $('.user-card');
    var circle = $($('.stars circle')[circleIndex]);
    var circleParent = circle.parent();
    userCard.find('.angel-img').attr('src', imgSrc);
    userCard.find('.angel-name').text(name);
    if (donation_date) {
        userCard.find('.angel-time').text(donation_date);
    }
    $('.stars').prepend(userCard);
    $('.user-card').fadeIn();
    var cardWidth = 140;
    var cardHeight = 160;
    userCard.css({
        left: ((circleParent.offset().left + parseInt(circle.attr('cx'))) * circleParent.outerWidth() / 873.296875) - (cardWidth / 2),
        top: circleParent.offset().top + parseInt(circle.attr('cy')) - (cardHeight / 2)
    });
}
var angles_slide;
function random_angles(angels) {
    if (typeof window.reservedAngels != 'undefined' && window.reservedAngels.length) {
        // check if there is a queue to be shown
        var angelIndex = window.reservedAngels.shift();
    } else {
        var angelsNo = angels.length;
        var angelIndex = Math.floor(Math.random() * (angelsNo - 1 + 1)) + 1;
    }
    var angel = angels[angelIndex];
    while (!angel) {
        angelIndex = Math.floor(Math.random() * (angelsNo - 1 + 1)) + 1;
        angel = angels[angelIndex];
    }

    var circlesNo = $('circle').length;
    var circleIndex = angelIndex % circlesNo;
    var circle = $('.circle' + circleIndex);
    circle.addClass('active');
    showStar(circleIndex, angel.name, angel.picture_url, angel.donation_date);
    angles_slide = setTimeout(function () {
        random_angles(angels);
    }, 3000);
}

function stopPlayingAngels() {
    clearInterval(angles_slide);
    angles_slide = false;
}

$('.stars circle').click(function () {
    hideActiveStar();
    $(this).addClass('active');
    var circleIndex = $(this).parent().find('circle').index(this);

    var angelsNo = angels.length;
    var rand = Math.floor(Math.random() * (angelsNo - 1 + 1)) + 1;
    var angel = angels[rand];

    showStar(circleIndex, angel.name, angel.picture_url, angel.donation_date);
});

$('.stars-bg').on('click', function (event) {
    if ($('.user-card').length && // there is a card visible
        !$(event.target).is('circle') && // didn't clicked on a circle
        !$(event.target).is('.user-card') && // didn't clicked on a card
        !$(event.target).parents('.user-card').length &&// didn't clicked on a children of a card
        (angles_slide === false) // angels are not playing now
    ) {
        random_angles(angels)
    }
});
var notFoundAlert = $('#alertNotFound');

$(document).ready(function () {
    var searchMinLength = 3;

    $('#angels_name').autocomplete({
        source: function (request, response) {
            var term = request.term;
            term = term.trim().replace(/\s{2,}/g, ' '); // remove extra whitespaces


            if ((/\s+$/.test(request.term) || // if the last character entered is "space"
                    (term.split(" ").length > 1) // if cleared term has more than one word
                ) &&
                (term.length >= searchMinLength) // if length of cleared term is enough
            ) {
                var newRequest = {
                    angel_name: term,
                    _token: $('meta[name="csrf-token"]').attr('content') // add csrf_token to request body
                };

                $.post(searchUrl, newRequest, response);
            }
        },
        minLength: searchMinLength,
        select: function (event, ui) {
            ui.item.value = ""; // to clear text inside of input after selecting one item

            var angel = ui.item;
            var angelsIds = jQuery.map(angels, function (n, i) {
                return n.id;
            });
            var itemIndex = $.inArray(angel.id, angelsIds);

            if (itemIndex == -1) {
                itemIndex = angels.length;
                angels.push(angel);
            }

            var circlesNo = $('circle').length;
            var circleIndex = itemIndex % circlesNo;

            var circle = $('.circle' + circleIndex);
            circle.addClass('active');

            stopPlayingAngels();

            showStar(circleIndex, angel.name, angel.picture_url, angel.donation_date);
        },
    });
});

// $('.search-angel').on('submit', function (event) {
//     event.preventDefault();
//     $(this).addClass('loading');
//     $('.search-angel').prepend(notFoundAlert).find('.alert').slideUp();
//
//     var angels_name = $('#angels_name').val();
//     var tok = $('input[name=_token]').val();
//
//     if (angels_name.length > 3) {
//         window.clearTimeout(angles_slide);
//         $.ajax({
//             type: "POST",
//             url: "angels/find",
//             cache: false,
//             dataType: "json",
//             data: {
//                 angel_name: angels_name,
//                 _token: tok,
//             }
//         }).done(function (data) {
//             $('.search-angel').removeClass('loading');
//             if (data.status === false) {
//                 $('.search-angel').prepend(notFoundAlert).find('.alert').slideDown();
//             } else if (data.status === true) {
//                 var rand = Math.floor(Math.random() * (19 - 1 + 1)) + 1;
//                 var circle = $('.circle' + rand);
//                 circle.addClass('active');
//                 var circleIndex = circle.parent().find('circle').index(circle);
//
//                 var angelsIds = jQuery.map(angels, function(n, i){
//                     return n.id;
//                 });
//                 var foundedIndexes = [];
//
//                 $.each(data.angels, function (index, angel) {
//                     var itemIndex = $.inArray(angel.id, angelsIds);
//                     if(itemIndex > -1) {
//                         foundedIndexes.push(itemIndex);
//                     } else {
//                         foundedIndexes.push(angels.length);
//                         angels.push(angel);
//                     }
//                 });
//
//                 window.reservedAngels = foundedIndexes; // set queue to be shown in the future
//                 random_angles(angels);
//
//                 // showStar(circleIndex, Data.name, Data.picture_url, Data.donate_time);
//                 // $("html, body").animate({scrollTop: $('.user-card').offset().top - $('.user-card').outerHeight() - $('.main-menu').outerHeight()});
//             } else {
//                 alert('خطایی رخ داده است. دوباره سعی کنید.');
//             }
//         });
//     }
//     else {
//         alert('نام اهدا کننده را وارد نمایید.');
//         $('.search-angel').removeClass('loading');
//     }
//     //Do your ajax
//     //success: {
//     //                 showStar(circleIndex, name, imageSrc);
//     //               `$("html, body").animate({ scrollTop: $('.user-card').offset().top -  $('.user-card').outerHeight() - $('.main-menu').outerHeight()});
//     //               }
//     //fail: $('.search-angel').prepend(notFoundAlert).find('.alert').slideDown();
//     //always: $('.search-angel').removeClass('loading');
// });
/**
 * Created by Mohammad Hadi on 12/18/2016.
 */
