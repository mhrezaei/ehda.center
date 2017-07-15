function faq_new_reset_form() {
    document.new_faq_qs.reset();
}

function register_card_step_one($mode)
{
    if ($mode == 'start')
    {
        $('.stepOneBtn').hide();
        $('.stepOneForm').slideToggle();
        $('#name_first').focus();
    }
    else if($mode == 'stop')
    {
        $('.stepOneBtn').show();
        $('.stepOneForm').slideToggle();
    }
    else
    {
        alert('Error!');
    }
}

function volunteer_register_step_one($mode)
{
    if ($mode == 'start')
    {
        $('.stepOneBtn').hide();
        $('.pdf-book').hide();
        $('.stepOneForm').slideToggle();
        $('#name_first').focus();
    }
    else if($mode == 'stop')
    {
        $('.stepOneBtn').show();
        $('.pdf-book').show();
        $('.stepOneForm').slideToggle();
    }
    else
    {
        alert('Error!');
    }
}

function volunteer_send_sheet(element)
{
    var count = $('input:radio:checked').length;
    var text = $('#exam_count').text() + ' ' + forms_digit_fa('' + count + '');
    $('#exam_count').text(text);
    $(element).hide(500, function () {
        $('#exam_count').show();
        $('.btn-primary').show();
    });
}

function registerForm_validate()
{
    // if ($('#chRegisterAll').is(":checked") || $('#chRegisterHeart').is(":checked") || $('#chRegisterLung').is(":checked") ||
    //     $('#chRegisterLiver').is(":checked") || $('#chRegisterKidney').is(":checked") || $('#chRegisterPancreas').is(":checked") ||
    //     $('#chRegisterTissues').is(":checked"))
    // {
    //     return 0;
    // }
    // else
    // {
    //     return $('#chRegisterAll').attr('error-value');
    // }

    // disable organ check
    return 0;
}

function editForm_validate()
{
    // if ($('#chRegisterAll').is(":checked") || $('#chRegisterHeart').is(":checked") || $('#chRegisterLung').is(":checked") ||
    //     $('#chRegisterLiver').is(":checked") || $('#chRegisterKidney').is(":checked") || $('#chRegisterPancreas').is(":checked") ||
    //     $('#chRegisterTissues').is(":checked"))
    // {
    //     return 0;
    // }
    // else
    // {
    //     return $('#chRegisterAll').attr('error-value');
    // }

    // disable organ check
    return 0;
}

function volunteer_final_step_validate() {
    $check = $("input[name='activity[]']:checked").length;

    if ($check > 0)
    {
        return 0;
    }
    else
    {
        return 'حداقل یکی از فعالیت ها را انتخاب نمائید.';
    }
}

function volunteer_final_step_form_data() {
    document.volunteer_final_step.reset();
}

function register_step_second(string) {
    var $formID = '#registerForm';
    var $form = $($formID);

    $('#db-check').val(string);

    $($formID + ' input').prop('disabled', true);
    $($formID + ' select').prop('disabled', true);
    $($formID + ' .dropdown-toggle').prop('disabled', true);
    $($formID + ' .step_one_btn').slideToggle();

    $('#register_third_step').slideToggle();

    $('.btn-db-check').on('click', function () {
        $($formID + ' input').prop('disabled', false);
        $($formID + ' select').prop('disabled', false);
        $($formID + ' .dropdown-toggle').prop('disabled', false);
        $($formID + ' .step_one_btn').slideToggle();
        $($formID + ' .form-feed').hide();

        $('#register_third_step').hide();
    });
}

function register_third_step_validate() {
    $("#registerForm .form-feed").hide();
    return 0;
}


// angels
function hideActiveStar(){
    $('.user-card').fadeOut(400, function(){
        $(this).remove();
        $('.stars circle.active').removeClass('active');
    });
}

function showStar(circleIndex, name, imgSrc, donate_time) {
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
    // if (donate_time)
    // {
    //     userCard.find('.angel-time').text('اهدا: ' + donate_time);
    // }
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
    if(typeof window.reservedAngels != 'undefined' && window.reservedAngels.length) {
        // check if there is a queue to be shown
        var angelIndex = window.reservedAngels.shift();
    } else {
        var angelsNo = angels.length;
        var angelIndex = Math.floor(Math.random() * (angelsNo - 1 + 1)) + 1;
    }
    var angel = angels[angelIndex];

    if (!angel) {
        angelIndex = Math.floor(Math.random() * (angelsNo - 1 + 1)) + 1;
        angel = angels[angelIndex];
    }
    var circle = $('.circle' + angelIndex);
    circle.addClass('active');
    var circleIndex = circle.parent().find('circle').index(circle);
    showStar(circleIndex, angel.name, angel.picture_url, angel.donate_time);
    angles_slide = setTimeout(function () {
        random_angles(angels);
    }, 3000);
}