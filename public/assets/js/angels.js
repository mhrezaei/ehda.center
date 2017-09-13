function hideActiveStar() {
    $('.user-card').fadeOut(400, function () {
        $(this).remove();
        $('.stars circle.active').removeClass('active');
    });
}

function showNewAngelForm(event) {
    event.preventDefault();
    var bottomOfStars = $('.stars-bg').offset().top + $('.stars-bg').height();
    var mainMenuHeight = $('.main-menu').height();

    if ($('.new-angel-form-container').height() == 0) {

        // scroll to bottom of stars box
        $('body, html').scrollTop(bottomOfStars - $(window).height());

        // make form visible
        var h = $('.new-angel-form-container-inner').height();
        $('.new-angel-form-container').height(h);

        // animate stars box to top
    }

    $('body, html').animate(
        {scrollTop: Math.max(bottomOfStars - mainMenuHeight, 0)},
        500,
        "linear",
        function () {
            $('.new-angel-form-container').height('auto')
        }
    );
}

function hideNewAngelForm() {
    var bottomOfStars = $('.stars-bg').offset().top + $('.stars-bg').height();
    $('body, html').animate({scrollTop: Math.max(bottomOfStars - $(window).height(), 0)}, 500, function () {
        $('.new-angel-form-container').height('auto');
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
    // userCard.find('.angel-name').text(name);
    // if (donation_date) {
    //     userCard.find('.angel-time').text(donation_date);
    // }
    $('.stars').prepend(userCard);
    // console.log(userCard)
    $('.user-card').fadeIn();
    var cardWidth = 140;
    var cardHeight = 160;
    userCard.css({
        left: ((circleParent.offset().left + parseInt(circle.attr('cx'))) * circleParent.outerWidth() / 873.296875) - (cardWidth / 2),
        top: circleParent.offset().top + parseInt(circle.attr('cy')) - (cardHeight / 2) - $('.stars-bg').offset().top
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
    // angles_slide = setTimeout(function () {
    //     random_angles(angels);
    // }, 3000);
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

    stopPlayingAngels();
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
        delay: 1000,
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

            let angel = ui.item;
            let angelsIds = jQuery.map(angels, function (n, i) {
                return n.id;
            });
            let itemIndex = $.inArray(angel.id, angelsIds);

            if (itemIndex == -1) {
                itemIndex = angels.length;
                angels.push(angel);
            }

            let circlesNo = $('circle').length;
            let circleIndex = itemIndex % circlesNo;

            let circle = $('.circle' + circleIndex);
            circle.addClass('active');

            stopPlayingAngels();

            showStar(circleIndex, angel.name, angel.picture_url, angel.donation_date);

            $(event.target).val(ui.item.label);

            // To don't empty input field
            return false;
        },
        search: function (event, ui) {
            $('#alertNotFound').slideUp();
        },
        response: function (event, ui) {
            if (!ui.content || !$.isArray(ui.content)) {
                $('#alertNotFound').slideDown();
            }
        },
    });

    $('.show-form-btn-container button, .open-new-angel-form').click(showNewAngelForm);

    // $(window).scroll(function () {
    //     var bottomOfPage = $('html').scrollTop() + $(window).height();
    //     var bottomOfStars = $('.stars-bg').offset().top + $('.stars-bg').height();
    //     console.log(bottomOfStars)
    //     console.log(bottomOfPage)
    //
    //     if (bottomOfPage > bottomOfStars && $('.new-angel-form-container').height() == 0) {
    //         showNewAngelForm();
    //     }
    // });
});