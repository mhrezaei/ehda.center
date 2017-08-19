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
    window.fileManagerModalOptions = getValueOf(parent.window.fileManagerModalOptions);

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


    $(document).on({
        click: function () {
            useFile($(this).data('file'));
        }
    }, '.thumbnail');

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
}

function refreshGallery() {
    selectFolder($(".breadcrumb-folders .folder.current"));
}

function getFolderParents(folder) {
    var link = folder.children('a');
    return link.parents('.folder');
}

function eachUploadCompleter() {
    refreshGallery();
}

function getFileUrl(file) {
    return $("[data-file=\"" + file + "\"]").data('url');
}

function showFile(file) {
    var preview = window.fileManagerModalOptions.preview;
    if (preview) {
        $.ajax({
            url: route_preview,
            type: 'post',
            data: {
                file: file,
            },
            success: function (response) {
                parent.document.getElementById(preview).innerHTML = response;
            }
        });
    }
}

function getUrlParam(paramName) {
    var reParam = new RegExp('(?:[\?&]|&)' + paramName + '=([^&]+)', 'i');
    var match = window.location.search.match(reParam);
    return ( match && match.length > 1 ) ? match[1] : null;
}

function useFile(file) {

    function useTinymce3(url) {
        var win = tinyMCEPopup.getWindowArg("window");
        win.document.getElementById(tinyMCEPopup.getWindowArg("input")).value = url;
        if (typeof(win.ImageDialog) != "undefined") {
            // Update image dimensions
            if (win.ImageDialog.getImageData) {
                win.ImageDialog.getImageData();
            }

            // Preview if necessary
            if (win.ImageDialog.showPreviewImage) {
                win.ImageDialog.showPreviewImage(url);
            }
        }
        tinyMCEPopup.close();
    }

    function useTinymce4AndColorbox(url, field_name) {
        parent.document.getElementById(field_name).value = url;

        if (typeof parent.tinyMCE !== "undefined") {
            parent.tinyMCE.activeEditor.windowManager.close();
        }
        if (typeof parent.$.fn.colorbox !== "undefined") {
            parent.$.fn.colorbox.close();
        }
    }

    function useCkeditor3(url) {
        if (window.opener) {
            // Popup
            window.opener.CKEDITOR.tools.callFunction(getUrlParam('CKEditorFuncNum'), url);
        } else {
            // Modal (in iframe)
            parent.CKEDITOR.tools.callFunction(getUrlParam('CKEditorFuncNum'), url);
            parent.CKEDITOR.tools.callFunction(getUrlParam('CKEditorCleanUpFuncNum'));
        }
    }

    function useFckeditor2(url) {
        var p = url;
        var w = data['Properties']['Width'];
        var h = data['Properties']['Height'];
        window.opener.SetUrl(p, w, h);
    }

    function useModal(url) {
        let parentWindow = $(parent.document);

        // Set value in target element
        let targetInput = parentWindow.find('#' + window.fileManagerModalOptions.input);
        if (targetInput.length) {
            targetInput.val(url);
        }

        // Show file
        showFile(file);

        // Run callback
        let callBackValue = window.fileManagerModalOptions.callback;
        if (callBackValue) {
            parent.window.eval(callBackValue);
        }

        // Close modal
        parent.window.closeFileManagerModal()
    }

    var url = getFileUrl(file);
    var field_name = getUrlParam('field_name');
    var is_ckeditor = getUrlParam('CKEditor');
    var is_modal = window.fileManagerModalOptions.modal;
    var is_fcke = typeof data != 'undefined' && data['Properties']['Width'] != '';
    var file_path = url.replace(route_prefix, '');
    var modal = url.replace(route_prefix, '');


    if (
        window.opener ||
        window.tinyMCEPopup ||
        field_name ||
        getUrlParam('CKEditorCleanUpFuncNum') ||
        is_ckeditor ||
        is_modal
    ) {
        if (is_modal) {
            useModal(url, field_name);
        } else if (window.tinyMCEPopup) { // use TinyMCE > 3.0 integration method
            useTinymce3(url);
        } else if (field_name) {   // tinymce 4 and colorbox
            useTinymce4AndColorbox(url, field_name);
        } else if (is_ckeditor) {   // use CKEditor 3.0 + integration method
            useCkeditor3(url);
        } else if (is_fcke) {      // use FCKEditor 2.0 integration method
            useFckeditor2(url);
        } else {                   // standalone button or other situations
            window.opener.SetUrl(url, file_path);
        }

        if (window.opener) {
            window.close();
        }
    } else {
        // No WYSIWYG editor found, use custom method.
        window.opener.SetUrl(url, file_path);
    }
}
//end useFile