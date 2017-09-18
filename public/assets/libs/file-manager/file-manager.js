var getListXhr, getFileDetailsXhr;

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var multiSelect = false;
var caching = {};
// On Load Variables
var $window,
    //Details Shown Inside Sidebar (Hidden On Page Load)
    detailSidebar,
    footer,
    tabs;

var timers = {
    fileDetails: {}
};

jQuery(function ($) {
    if (parent.window.fileManagerModalOptions && parent.window.fileManagerModalOptions.multi) {
        multiSelect = true;
    }
    window.fileManagerModalOptions = getValueOf(parent.window.fileManagerModalOptions ? parent.window.fileManagerModalOptions : {});

    // Select default folder
    let defaultFolder = $(".breadcrumb-folders .folder").first();
    if (parent.window.fileManagerModalOptions && parent.window.fileManagerModalOptions.defaultFolder) {
        let defaultFolderParts = parent.window.fileManagerModalOptions.defaultFolder.split('___');
        console.log(defaultFolderParts)
        if ($.isArray(defaultFolderParts) && (defaultFolderParts.length == 2)) {
            let tmpDefaultFolder =
                $('.breadcrumb-folders .folder[data-instance="' + defaultFolderParts[0] + '"][data-key="' + defaultFolderParts[1] + '"]');
            if (tmpDefaultFolder.length) {
                defaultFolder = tmpDefaultFolder;
            }
        }
    }
    selectFolder(defaultFolder);

    $window = $(window);
    //Details Shown Inside Sidebar (Hidden On Page Load)
    detailSidebar = $('.media-sidebar');
    footer = $('.media-footer');
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
        filter: "li:not(.unselectable)",
        selecting: function (event, ui) {
            //ul Containing The Whole Thumbnails
            var ul = $(this),
                //Current li Selected
                currentEl = $(ui.selecting);

            // Deselect other selected items if multi selection is disabled
            if (!multiSelect) {
                ul.find('.ui-selected').removeClass('ui-selected');
            }
        },
        stop: function (event, ui) {
            //All Selected "li"
            var selected = $('li.ui-selected');

            if (multiSelect) {
                showFileDetails(selected.first());
            } else {
                selected.not(':first').removeClass('ui-selected');
                selected = selected.first();
                showFileDetails(selected);
            }

            refreshSelection(selected);
        },
    });

    $('#thumbnail').find('.ui-selectee').each(function () {
        if (!$(this).is('li')) {
            $(this).removeClass('.ui-selectee');
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
            let that = $(this);
            let file = that.closest('.file-details').attr('data-file');
            if (file) {
                let data = {fileKey: file};
                $.ajax({
                    url: urls.deleteFile,
                    type: 'POST',
                    data: data,
                    beforeSend: function () {
                        that.addClass('in-process');
                        loadingDialog('show', '#sidebar-loading');
                    },
                    success: function () {
                        refreshGallery();
                        that.removeClass('delete-file-btn');
                        that.addClass('restore-file-btn');
                        that.html(lang['menu-restore']);
                        $('.deletion-message').html(lang['message-file-deleted']).show();
                    },
                    complete: function () {
                        that.removeClass('in-process');
                        loadingDialog('hide', '#sidebar-loading');
                    }
                })
            }
        }
    }, '.delete-file-btn:not(.in-process)');

    $(document).on({
        click: function () {
            let that = $(this);
            let file = that.closest('.file-details').attr('data-file');
            if (file) {
                let data = {fileKey: file};
                $.ajax({
                    url: urls.restoreFile,
                    type: 'POST',
                    data: data,
                    beforeSend: function () {
                        that.addClass('in-process');
                        loadingDialog('show', '#sidebar-loading');
                    },
                    success: function () {
                        refreshGallery();
                        that.removeClass('restore-file-btn');
                        that.addClass('delete-file-btn');
                        that.html(lang['menu-delete']);
                        $('.deletion-message').hide().html();
                    },
                    error: function (rs) {
                        if (rs.status == 404) {
                            $('.deletion-message').html(lang['error-file-not-found']).show();
                        }
                    },
                    complete: function () {
                        that.removeClass('in-process');
                        loadingDialog('hide', '#sidebar-loading');
                    }
                })
            }
        }
    }, '.restore-file-btn:not(.in-process)');

    $(document).on({
        keyup: function () {
            updateFileDetail($(this))
        },
        change: function () {
            updateFileDetail($(this))
        }
    }, '.setting :input');

    $(document).on({
        click: function (event) {
            event.preventDefault();
            let that = $(this);
            let instance = that.data('instance');
            let key = that.data('key');

            if (instance && key) {
                let folder = $('.folder[data-instance="' + instance + '"][data-key="' + key + '"]');
                if (folder.length == 1) {
                    selectFolder(folder);
                }
            }
        }
    }, '.btn-open-folder');

    $(document).on({
        click: function (event) {
        }
    }, '.delete-upload-info');

    $('.file-list-view').scroll(function () {
        let scrolled = $(this).scrollTop();
        $('#loading-dialog').css('top', scrolled + 'px')
    });

    $('.attachments-preview').on({
        click: function () {
            let that = $(this);
            let fileKey = that.data('file');

            if (fileKey) {
                showFileDetails($(this).closest('li'))
            }
        }
    }, '.thumbnail');

    $('#add-btn').click(function () {
        var selected = $('li.ui-selected .thumbnail');

        if (selected.length) {
            if (multiSelect) {
                useFiles(selected);
            } else {
                useFile(selected.first());
            }
        } else {
            alert(lang['error-file-empty']);
        }
    });

    $('#filters').find(':input').change(function () {
        refreshGallery();
    });

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
    let parents = getFolderParents(folder);

    let externalFields = {};
    parents.each(function () {
        externalFields[$(this).attr('data-instance')] = $(this).attr('data-key');
    });
    $('.uploader-container').find('#externalFields').val(JSON.stringify(externalFields));

    //Reseting Folder Icons
    $(".breadcrumb-folders .folder").removeClass("active");
    $(".folder .folder-icon").removeClass("fa-folder-open").addClass('fa-folder');
    $(".breadcrumb-folders li").removeClass('current');

    folder.addClass('current');
    parents.addClass('active');

    $('.breadcrumb-folders .active').each(function (i) {
        $(this).find('span.folder-icon').first().removeClass('fa-folder').addClass("fa-folder-open");
    });

    let fileTypes = acceptedFilesCategories[folder.data('instance') + '__' + folder.data('key')];
    $('#filter-file-type option').each(function () {
        let opt = $(this);
        if (opt.val() && ($.inArray(opt.val(), fileTypes) == -1)) {
            opt.hide();
        } else {
            opt.show();
        }
    });

    resetFilters();
    refreshGallery();
}

function resetFilters() {
    $('#filters').find(':input').each(function () {
        let that = $(this);
        if (that.is('select')) {
            let firstVisibleOpt = that.find('option:visible').first();
            that.val(firstVisibleOpt.val()).change();
        } else {
            that.val('');
        }
    });
}

function refreshGallery() {
    // selectFolder($(".breadcrumb-folders .folder.current"));
    let folder = $(".breadcrumb-folders li.current");
    let listRequest = $('#filters').find(':input').serializeObject();
    listRequest['instance'] = folder.attr('data-instance');
    listRequest['key'] = folder.attr('data-key');
    getListXhr = $.ajax({
        url: urls.getList,
        type: 'POST',
        data: listRequest,
        beforeSend: function () {
            if (getListXhr && getListXhr.readyState != 4) {
                getListXhr.abort();
            }
            loadingDialog();
        },
        success: function (response) {
            $('#thumbnail').html($(response));

            // $('#thumbnail').find('li').each(function () {
            //     let li = $(this);
            //     let img = li.find('img')
            //     if (img.length && img.hasClass('not-found'))
            //         li.addClass('unselectable');
            // });
            loadingDialog('hide');
            refreshSelection();
        }
    });

}

function refreshSelection(selected) {
    if (typeof seleted == 'undefined') {
        selected = $('li.ui-selected');
    }

    selected = selected.filter(function (key, obj) {
        let item = $(obj);
        if (!item.length) {
            return false;
        }

        let img = item.find('img');
        if (!img.length) {
            return false;
        }

        if (img.hasClass('not-found')) {
            item.removeClass('ui-selected');
            return false;
        }

        return true;
    });

    if (selected.length) {
        $('#add-btn').removeAttr('disabled');
    } else {
        $('#add-btn').attr('disabled', 'disabled');
    }

    let selectedClone = selected.clone().removeAttr('class'),
        selectedCount = selectedClone.length,
        PersianCount = pd(selectedCount);

    //Adding Selected Items Into Footer Preview
    footer.find('.attachments-preview').empty().append(selectedClone);
    footer.find('.count').empty().text("گزینش شده: " + PersianCount);

    //Setting Selected Elements As Button Value
    $('#add-btn').val(selectedClone);
}

function switchToGallery() {
    tabs.filter('[data-page=gallery]').trigger('click');
}

function getFolderParents(folder) {
    var link = folder.children('a');
    return link.parents('.folder');
}

function eachUploadCompleted(file, dropzoneEl) {
    if (file.status == 'success') {
        setTimeout(function () {
            dropzoneEl.removeFile(file);
        }, 2000)
    }

    refreshGallery();
}

function allUploadsCompleted(acceptedFiles, files, dropzoneEl) {
    if ($.inArray('error', files.getColumn('status')) == -1) {
        setTimeout(switchToGallery, 1000);
    }
}

function getFileUrl(file) {
    return $("[data-file=\"" + file + "\"]").data('url');
}

function getFilePathname(file) {
    return $("[data-file=\"" + file + "\"]").data('pathname');
}

function getUrlParam(paramName) {
    var reParam = new RegExp('(?:[\?&]|&)' + paramName + '=([^&]+)', 'i');
    var match = window.location.search.match(reParam);
    return ( match && match.length > 1 ) ? match[1] : null;
}

function showFileDetails(li) {
    if (!li.is('li')) {
        console.log('!li.is(\'li\')')
        return false;
    }

    if (!li.length) {
        console.log('!li.length')
        return false;
    }

    let thumb = li.find('.thumbnail');
    if (!thumb.length) {
        console.log('!thumb.length')
        return false;
    }

    let hashid = thumb.data('file');
    if (!hashid) {
        return false;
    }


    let thumbnail = $('ul.ui-selectable li .thumbnail[data-file=' + hashid + ']');
    if (!thumbnail.length) {
        console.log('!li.length')
        return false;
    }
    let ul = thumbnail.closest('ul');

    //Showing Sidebar If Hidden
    if (!detailSidebar.is(':visible')) {
        detailSidebar.show();
    }

    //Showing Details Inside Sidebar
    detailSidebar.find('.file-details').show();

    // Getting file details
    getFileDetailsXhr = $.ajax({
        url: urls.getFileDetails + '/' + hashid,
        beforeSend: function () {
            if (getFileDetailsXhr && getFileDetailsXhr.readyState != 4) {
                getFileDetailsXhr.abort();
            }
        },
        success: function (response) {

            $('.file-details').attr('data-file', hashid);
            $('.file-details').html($(response));

            $('.setting :input').each(function () {
                let timerName = $(this).attr('name') + '-' + $.now();
                timers.fileDetails[timerName] = new Timer();
                $(this).attr('data-timer', timerName);
            });
        }
    });

    // Resetting Active Class To Currently Selected Element
    ul.find('.active').removeClass('active');
    thumbnail.closest('li').addClass('active');
}

function updateFileDetail(input) {
    let file = input.closest('.file-details').attr('data-file');
    let inputName = input.attr('name');
    let inputValue = input.val();
    if (file && (typeof inputName !== 'undefined') && (typeof inputValue !== 'undefined')) {
        let data = {fileKey: file};
        let timerName = input.attr('data-timer');
        let timer = timers.fileDetails[timerName];
        inputValue = $.trim(inputValue);
        data[inputName] = inputValue;

        timer.delay(function () {
            $.ajax({
                url: urls.setFileDetails,
                type: 'POST',
                data: data,
                beforeSend: function () {
                    loadingDialog('show', '#sidebar-loading');
                },
                complete: function () {
                    loadingDialog('hide', '#sidebar-loading');
                }
            })
        }, 1);
    }
}

function useFile(thumbEl) {

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

    function useModal(result) {
        let parentWindow = $(parent.document);

        // Set value in target element
        let targetInput = parentWindow.find('#' + window.fileManagerModalOptions.input);
        if (targetInput.length) {
            targetInput.val(result);
        }

        // Show file
        showFile(hashid);

        // Run callback
        let callBackValue = window.fileManagerModalOptions.callback;
        if (callBackValue) {
            parent.window.eval(callBackValue);
        }

        // Close modal
        parent.window.closeFileManagerModal()
    }

    var hashid = thumbEl.data('file');
    var url = getFileUrl(hashid);
    var pathname = getFilePathname(hashid);
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
            switch (window.fileManagerModalOptions.outputType) {
                case 'hashid':
                    useModal(hashid, field_name);
                    break;
                case 'url':
                    useModal(url, field_name);
                    break;
                default:
                    useModal(pathname, field_name);
                    break
            }
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

function useFiles(filesEls) {
    function showFiles(hashids) {
        var preview = window.fileManagerModalOptions.preview;
        if (preview) {
            $.ajax({
                url: route_preview,
                type: 'post',
                data: {
                    file: hashids.join('-'),
                },
                success: function (response) {
                    parent.document.getElementById(preview).innerHTML = response;
                }
            });
        }
    }

    function useModal(result) {
        let parentWindow = $(parent.document);

        // Set value in target element
        let targetInput = parentWindow.find('#' + window.fileManagerModalOptions.input);
        if (targetInput.length) {
            targetInput.val(JSON.stringify(result));
        }

        // Show file

        showFiles(hashids);

        // Run callback
        let callBackValue = window.fileManagerModalOptions.callback;
        if (callBackValue) {
            parent.window.eval(callBackValue);
        }

        // Close modal
        parent.window.closeFileManagerModal()
    }

    var hashids = [];
    var is_modal = window.fileManagerModalOptions.modal;

    filesEls.each(function () {
        hashids.push($(this).data('file'));
    });

    if (is_modal) {
        let result = [];
        switch (window.fileManagerModalOptions.outputType) {
            case 'hashid':
                result = hashids;
                break;
            case 'url':
                filesEls.each(function () {
                    result.push($(this).data('url'));
                });
                break;
            default: // Ex: pathname
                filesEls.each(function () {
                    result.push($(this).data('pathname'));
                });
                break
        }

        useModal(result);
    }
}

function loadingDialog(parameter, dialog) {
    if (isDefined(dialog)) {
        if (!(dialog instanceof HTMLCollection) && (typeof dialog == 'string')) {
            dialog = $(dialog);
        }
    } else {
        dialog = $('#loading-dialog');
    }

    if (dialog.length) {
        if ($.inArray(parameter, ['hide', false, 0]) > -1) {
            dialog.hide();
        } else {
            dialog.show();
        }
    }
}
