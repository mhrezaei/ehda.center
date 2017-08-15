String.prototype.replaceAll = function (search, replacement) {
    var target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

jQuery(function ($) {
    selectFolder($(".breadcrumb-folders .folder").first())

    // On Load Variables
    var $window = $(window),

        //Details Shown Inside Sidebar (Hidden On Page Load)
        detailSidebar = $('.media-sidebar'),
        footer = $('.media-footer'),
        tabs = $('.media-router .media-menu-item');


    /*---Breadcrumb Function----*/
    $(".breadcrumb-folders li a").click(function (e) {
        e.preventDefault();
        var folder = $(this).closest('.folder');
        selectFolder(folder)
    });
    /*---- End Breadcrumb Function----*/


    /*---- Handeling Sidebar And Folder Browser-----*/
    $window.on('resize load', function () {

        var windowWidth = $window.width();

        /*---- Folder Browser Opening Function-----*/
        if (windowWidth <= 900) {
            //Closing Browser If Open
            $('.media-folder-menu').hide();

            //Setting Browser Opening Icon Function
            $('.media-frame-title .menu-icon').on('click', function () {
                $('.media-folder-menu').show();
            });

            //Setting Browser Closing Icon Function
            $('.media-folder-menu .close-sidebar').on('click', function () {
                $('.media-folder-menu').hide();
            });
        } else {

            //Always Show Browser On Bigger Screens
            $('.media-folder-menu').show();
        }
        /*---- End Folder Browser Opening Function-----*/

        /*---- Siderbar Opening Function -----*/
        if (windowWidth <= 768) {

            //Closing Sidebar If Open
            $('.media-sidebar').hide();

            //Setting Sidebar Closing Icon Function
            $('.media-sidebar .close-sidebar').on('click', function () {
                $('.media-sidebar').hide();
            });
        } else {

            //Always Show Sidebar On Bigger Screens
            $('.media-sidebar').show();
        }
        /*---- Siderbar Opening Function -----*/

    });


    /*----Selecting Thumbnails------*/
    $('#thumbnail').selectable({
        selecting: function (event, ui) {

            //ul Containing The Whole Thumbnails
            var ul = $(this),
                //Current li Selected
                currentEl = $(ui.selecting),
                imgSrc = currentEl.find('img').attr('src'),
                imgName = imgSrc.replace('img/', '');

            //Showing Sidebar If Hidden
            if (!detailSidebar.is(':visible')) {
                detailSidebar.show();
            }

            //Showing Details Inside Sidebar
            detailSidebar.find('.file-details').show();

            //Inserting Image Data To Sidebar (Should Be Done "Dynamically"!)
            detailSidebar.find('.thumbnail-image img').attr('src', imgSrc);
            detailSidebar.find('.details .filename').empty().text(imgName);

            //Reseting Active Class To Currently Selected Element
            ul.find('.active').removeClass('active');
            currentEl.addClass('active');
        },
        stop: function (event, ui) {
            //All Selected "li"s
            var selected = $('li.ui-selected').clone().removeClass('active ui-selected'),
                selectedCount = selected.length,
                PersianCount = pd(selectedCount);

            //Adding Selected Items Into Footer Preview
            footer.find('.attachments-preview').empty().append(selected);
            footer.find('.count').empty().text("گزینش شده: " + PersianCount);

            //Setting Selected Elements As Button Value
            $('#add-btn').val(selected);

        }
    });
    /*----End Selecting Thumbnails----*/


    /*-----Clear List And Reseting -----*/
    $('#clear-list').on('click', function () {

        //Removing Selected Elements Stylings
        $('li.ui-selected').removeClass('active ui-selected');

        //Hide Details In Sidebar
        detailSidebar.find('.file-details').hide();

        //Empty Footer Preview
        footer.find('.attachments-preview').empty('active ui-selected');
        footer.find('.count').empty().text("گزینش شده: " + pd("0"));

        //Unset Button Value
        $('#add-btn').val("");
    });
    /*-----End Clear List And Reseting -----*/


    /*-----Tab Changing Function -----*/
    tabs.on('click', function (e) {
        e.preventDefault();
        tabs.removeClass('active');
        var pageId = $(this).addClass('active').data('page');
        $('.page').hide();
        $('#' + pageId).show();


    });
    /*-----End Tab Changing Functions-----*/


}); //End Of Ready!

function forms_pd($string) {
    if (!$string) {
        $string = "0";
    }
    $string = $string.toString();

    $string = $string.replaceAll(/1/g, "۱");
    $string = $string.replaceAll(/2/g, "۲");
    $string = $string.replaceAll(/3/g, "۳");
    $string = $string.replaceAll(/4/g, "۴");
    $string = $string.replaceAll(/5/g, "۵");
    $string = $string.replaceAll(/6/g, "۶");
    $string = $string.replaceAll(/7/g, "۷");
    $string = $string.replaceAll(/8/g, "۸");
    $string = $string.replaceAll(/9/g, "۹");
    $string = $string.replaceAll(/0/g, "۰");

    return $string;
}

function forms_digit_fa(enDigit) {
    return forms_pd(enDigit);

    var newValue = "";
    for (var i = 0; i < enDigit.length; i++) {
        var ch = enDigit.charCodeAt(i);
        if (ch >= 48 && ch <= 57) {
            var newChar = ch + 1584;
            newValue = newValue + String.fromCharCode(newChar);
        }
        else {
            newValue = newValue + String.fromCharCode(ch);
        }
    }
    return newValue;
}

function pd(enDigit) {
    return forms_digit_fa(enDigit);
}

function selectFolder(folder) {
    //Finds Activated Folders And Changes Icons
    var parents = getFolderParents(folder);

    //Reseting Folder Icons
    $(".breadcrumb-folders .folder").removeClass("active");
    $(".folder .folder-icon").removeClass("fa-folder-open").addClass('fa-folder');
    $(".breadcrumb-folders li").removeClass('current');

    folder.addClass('current');
    parents.addClass('active');

    $('.breadcrumb-folders .active').each(function (i) {
        $(this).find('span.folder-icon').first().removeClass('fa-folder').addClass("fa-folder-open");
    });

    var listRequest = new FormData();
    listRequest.append('key', folder.attr('data-key'));
    listRequest.append('instance', folder.attr('data-instance'));
    listRequest.append('_token', csrfToken);
    $.ajax({
        url: urls.getList,
        type: 'POST',
        data: {
            key: folder.attr('data-key'),
            instance: folder.attr('data-instance'),
        },
        success: function (response) {
            $('#thumbnail').replaceWith($(response));
        }
    });
    console.log(listRequest);
}

function getFolderParents(folder) {
    var link = folder.children('a');
    return link.parents('.folder');
}