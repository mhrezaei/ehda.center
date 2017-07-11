/**
 * Created by Yasna-PC1 on 10/07/2017.
 */
$(document).ready(function () {

    lowLag.init({'urlPrefix': siteUrl + '/assets/sound/'});
    lowLag.load(['heartSound.mp3', 'heartSound.ogg'], 'pluck1');

    $.ajax({
        type: "GET",
        url: siteUrl + "/files/ecg/xmls/data.xml",
        dataType: "xml",
        success: function (xml) {
            xml_data = xml;
            $('.rhy_item.selected').click();
        },
        error: function () {
            alert("An error occurred while processing XML file.");
        }
    });

    $('.rhy_item').click(function () {
        var element = $(this);
        var str = element.attr('id');
        clearInterval(sound_var);
        loadGraph(str);
    });

    $('.monitor-preview-diagram div.static-diagram').mouseenter(function () {
        $(this).css('animation-play-state', 'paused');
    }).mouseleave(function () {
        $(this).css('animation-play-state', 'running');
    });
});

var xml_data = null;
var not_play = false;
var play_sound = true;
var sound_var = 0;

function loadGraph(str) {
    var rhyParts = str.split('_');

    var hr = rhyParts[1];
    assignHartRate(hr);

    var r = String(rhyParts[0]).toLowerCase();
    changeGraphPreview(r);
    temp_str = String($(xml_data).find('rs').find(String(r)).text()).split(",");
    hr_abc = temp_str.length;

    var rhy_info = $(xml_data).find('item').eq(parseInt(rhyParts[2]));
    $('.rhy-title').html($.trim(rhy_info.find("data_title").text()));
    $('.rhy-description').html($.trim(rhy_info.find("data_des").text()));

    if (r == "r3" || r == "r15" || r == "r8" || r == "r19") {
        sound_var = setInterval(showCC1, 20000 / hr_abc);
    } else {
        sound_var = setInterval(showCC, 10);
    }
}


function assignHartRate(rate) {
    $('.hr-value').html(rate);
}

function changeGraphPreview(rhy) {
    $('.monitor-preview-diagram div.static-diagram').attr('id', String(rhy).toLowerCase() + 'rhy');
}

function showCC() {
    if (String($('.monitor-preview-diagram div.static-diagram').css("color")) != "rgb(255, 255, 255)" && !not_play) {
        not_play = true;
        playSound("heartsound");
        pulheart();
    } else if (String($('.monitor-preview-diagram div.static-diagram').css("color")) == "rgb(255, 255, 255)") {
        not_play = false;
    }
}
function showCC1() {
    console.log('beating1');
    playSound("heartsound");
    pulheart();
}

function playSound(id) {
    if (!play_sound) {
        return false;
    }
    switch (id) {
        case "heartsound":
            lowLag.play('pluck1');
            break;
        case "wrong":
            boing.play();
            break;
        case "right":
            right1.play();
            break;
        case "wellDone":
            wellDone.play();
            break;
        case "helpMe":
            helpMe.play();
            break;
        case "congrats":
            congrats.play();
            break;

    }
}


function pulheart() {
    $('.hr-value').addClass("pulse");
    var wait = window.setTimeout( function(){
            $('.hr-value').removeClass("pulse")},
        200
    );
}