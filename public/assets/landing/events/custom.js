/* Theme Name: Tripaco - Responsive Coming Soon Template
   Author: Krishthemes77
   Author e-mail: themesbykrish@gmail.com
   Version: 1.0
   Created: september 2016
   File Description:Main JS file of the template
*/

!function($) {
    "use strict";


    var ContactForm = function() {
        this.$contactForm = $("#ajax-form"),
        this.$errorMessages = $("#err-form"),
        this.$confirmMessage = $("#success-form")
    };
    //contact form submit handler
    ContactForm.prototype.submitForm = function(e) {
        var $this = this;
        $this.$errorMessages.fadeOut('slow'); // reset the error messages (hides them)

        var data_string = $this.$contactForm.serialize(); // Collect data from form
        $.ajax({
            type: "POST",
            url: $this.$contactForm.attr('action'),
            data: data_string,
            timeout: 6000,
            cache: false,
            crossDomain: false,
            error: function (request, error) {
                $this.$errorMessages.html('An error occurred: ' + error + '');
            },
            success: function () {
                $this.$confirmMessage.show(500).delay(4000).animate({
                    height: 'toggle'
                    }, 500, function () {
                    }
                );
            }
        });
        return false;
    },

    ContactForm.prototype.init = function () {
        var $this = this;
        //initializing the contact form
        this.$contactForm.parsley().on('form:submit', function() {
            $this.submitForm();
            return false;
        });
    },
    $.ContactForm = new ContactForm, $.ContactForm.Constructor = ContactForm

}(window.jQuery),


function($) {
    "use strict";

    var SubscribeForm = function () {
        this.$subscribeForm = $("#subscribe-form")
    };
    SubscribeForm.prototype.init = function () {
        var $this = this;
        //initializing the form using ajaxChimp
        this.$subscribeForm.ajaxChimp({});
    },
    $.SubscribeForm = new SubscribeForm, $.SubscribeForm.Constructor = SubscribeForm
}(window.jQuery),

function($) {
    "use strict";

    var Tripaco = function() {
        this.$preLoaderEl = $('#preloader'),
        this.$loaderStatus = $("#status"),
        this.$fullPageEl = $('#fullpage'),
        this.$countdown =  $('.countdown-time'),
        this.$backToTop = $('#back-to-top'),
        this.$contactForm = $("#ajax-form"),
        this.$subscribeForm = $("#subscribe-form"),
        this.$aboutSection = $('.about-chart'),
        this.$gradientEl = $('#gradient'),
        this.$YoutubeVideoEl = $(".youtube-video-bg")
    };

    //creates time circles
    Tripaco.prototype.createTimeCircles = function() {
        var $this = this;
        $this.$countdown.each(function(idx, ct) {
            $(ct).addClass('animated bounceIn');

            var circle_color = $(ct).attr('circle-color');
            var bg_color = $(ct).attr('bg-color');

            $(ct).TimeCircles({
                bg_width: 0.5,
                fg_width: 0.03,
                circle_bg_color: bg_color,
                time: {
                    Days: {color: circle_color},
                    Hours: {color: circle_color},
                    Minutes: {color: circle_color},
                    Seconds: {color: circle_color}
                }
            });

            $(ct).on('webkitAnimationEnd mozAnimationEnd oAnimationEnd animationEnd', function() {
                $(ct).removeClass('animated bounceIn');
            });
        });
    },
    //init youtube video background if any
    Tripaco.prototype.initYtVideoBackground = function() {
        var $this = this;
        $this.$YoutubeVideoEl.each(function (idx, yt) {
            var video_id = $(yt).attr("data-video-id");
            var options = { videoId: video_id, repeat: true }; /* you tube video id goes here.*/
            $(yt).tubular(options);
        });
    },
    //init about us
    Tripaco.prototype.initAbout = function() {
        this.$aboutSection.waypoint(function(){
            $('.easy-pie-chart').each(function () {
                var $this, $parent_width, $chart_size, height;
                $this = $(this);
                $parent_width = $(this).parent().width();
                $chart_size = $this.attr('data-size');
                if ($parent_width < $chart_size) {
                    $chart_size = $parent_width;
                }

                var bg_color = $this.attr('data-bgcolor');
                var track_color = $this.attr('data-trackcolor');
                
                height = parseInt($chart_size) + 30;

                $this.css('line-height', $chart_size + "px");
                $this.css('height', height + "px");

                $this.easyPieChart({
                    animate: 2000,
                    lineCap: 'butt',
                    lineWidth: 5,
                    size: 150,
                    scaleColor: false,
                    barColor: bg_color,
                    trackColor: track_color,
                    onStep: function (from, to, value) {
                        $(this.el).find('.percent-container .percent').html(Math.ceil(value) + "");
                        var $info =  $(this.el).find('.info');
                        $info.css("margin-left", -($info.width() / 2));

                    }
                });

            });
            
        },{offset: '80%'});
    },
    //init body background
    Tripaco.prototype.initBodyBg = function() {
        //setting bg
        if($('body').hasClass('slideshow-background')) { // SLIDESHOW BACKGROUND - 
            // i.e. bg-image="images/bg1.jpg,images/bg2.jpg,images/bg3.jpg,images/bg4.jpg,images/bg5.jpg
            var bgs = $("body").attr("bg-image").split(",");
            $("body").backstretch(bgs, {duration: 3000, fade: 1200});
        }
        else if($('body').hasClass('image-background')) { // IMAGE BACKGROUND
            $("body").backstretch($("body").attr("bg-image"));
        }

        //if parallax
        if($("#parallaxbg").length >0) {
            $(window).parallaxmouse({
                invert: true,
                range: 400,
                elms: [
                    {el: $('#parallaxbg'), rate: 0.1},

                ]
            });
        }
    },
    //init page with gradient
    Tripaco.prototype.initGradientColor = function() {
        var $this = this;
        var colors = new Array(
            [82,82,82,0.7],
            [255,249,76,0.5],
            [85,172,238,0.6],
            [255,75,31,0.7],
            [17,67,87,0.4],
            [241,242,181,0.8],
            [242,148,146,0.6]);

        var start = 0; 
        var colorGenerate = [0,1,2,3];

        //transition speed
        var gradientSpeed = 0.005;

        function generateGradient(gradientElement) {
            if ( $===undefined ) 
                return;

            var a = colors[colorGenerate[0]];
            var b = colors[colorGenerate[1]];
            var c = colors[colorGenerate[2]];
            var d = colors[colorGenerate[3]];

            var istart = 1 - start;
            var r1 = Math.round(istart * a[0] + start * b[0]);
            var g1 = Math.round(istart * a[1] + start * b[1]);
            var b1 = Math.round(istart * a[2] + start * b[2]);
            var color1 = "rgb("+r1+","+g1+","+b1+")";

            var r2 = Math.round(istart * c[0] + start * d[0]);
            var g2 = Math.round(istart * c[1] + start * d[1]);
            var b2 = Math.round(istart * c[2] + start * d[2]);
            var color2 = "rgb("+r2+","+g2+","+b2+")";

            gradientElement.css({
              background: "-webkit-gradient(linear, left bottom, right top, from("+color1+"), to("+color2+"))"}).css({
              background: "-moz-linear-gradient(left, "+color1+" 0%, "+color2+" 100%)"});
            
            start += gradientSpeed;
            if ( start >= 1 ) {
              start %= 1;
              colorGenerate[0] = colorGenerate[1];
              colorGenerate[2] = colorGenerate[3];           
              //pick two new target color indices
              //do not pick the same as the current one
              colorGenerate[1] = ( colorGenerate[1] + Math.floor( 1 + Math.random() * (colors.length - 1))) % colors.length;
              colorGenerate[3] = ( colorGenerate[3] + Math.floor( 1 + Math.random() * (colors.length - 1))) % colors.length;
            }
        }

        setInterval(function() {
            generateGradient($this.$gradientEl);
        }, 10);
    },
    //init
    Tripaco.prototype.init = function () {
        var $this = this;
        //window related event

        //Handling load event
        $(window).on('load', function() {
            var windowHeight = $(window).height();

            //hiding the loader on window load
            $this.$loaderStatus.fadeOut();
            $this.$preLoaderEl.delay(350).fadeOut('slow');
            $('body').delay(350).css({
                'overflow': 'visible'
            });
        });

        //doc ready
        $(document).ready(function(e) {
            //setting body background
            $this.initBodyBg();

            //if gradient - animated
            if($this.$gradientEl.length>0) {
                $this.initGradientColor();
            }

            //if youtube video
            $this.initYtVideoBackground();

            //init full page
            $this.$fullPageEl.fullpage({
                anchors: ['1stPage', '2ndPage', '3rdPage', '4thPage', '5thPage'],
                menu: '#menu',
                scrollingSpeed: 800,
                autoScrolling: true,
                scrollBar: true,
                easing: 'easeInQuart',
                resize : false,
                paddingTop: '0px',
                paddingBottom: '80px',
                navigation: true,
                navigationPosition: 'right',
                navigationTooltips: ['Home', 'Subscribe', 'About','Services','Contact'],
                responsiveWidth: 1100,
            });
            

            //init countdown
            $this.createTimeCircles();

            //init about us section
            $this.initAbout();
        });

        //Handling the resize event
        $(window).on('resize', function() {
            var windowHeight = $(window).height();
            //re-init circles
            $this.$countdown.each(function(idx, ct) {
                $(ct).TimeCircles().destroy(); 
            });
            
            $this.createTimeCircles();
        });

        //Handling the scroll event
        $(window).scroll(function(){
            
        }); 

        //init contact app if contact form added in a page
        if(this.$contactForm.length>0)
            $.ContactForm.init();

        //init subscribe app if form is added in a page
        if(this.$subscribeForm.length>0)
            $.SubscribeForm.init();
    },
    //init
    $.Tripaco = new Tripaco, $.Tripaco.Constructor = Tripaco
}(window.jQuery),

//initializing
function($) {
    "use strict";
    $.Tripaco.init()
}(window.jQuery);