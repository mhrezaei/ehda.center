/**
 * Created by Yasna-PC1 on 12/08/2017.
 */

// if we miss this command, every elements with "dropzone" class will be automatically change to dropzone
Dropzone.autoDiscover = false;

// setting default options for all dropzone uploaders in this page
Dropzone.prototype.defaultOptions.url = dropzoneRoutes.upload;
Dropzone.prototype.defaultOptions.addRemoveLinks = true;
Dropzone.prototype.defaultOptions.dictRemoveFile = "";
Dropzone.prototype.defaultOptions.dictCancelUpload = "";
Dropzone.prototype.defaultOptions.dictFileTooBig = messages.errors.size;
Dropzone.prototype.defaultOptions.dictInvalidFileType = messages.errors.type;
Dropzone.prototype.defaultOptions.dictResponseError = messages.errors.server;
Dropzone.prototype.defaultOptions.dictMaxFilesExceeded = messages.errors.size;

// dropzone Options
var dropzoneOptions = {
    init: {
        sending: function (file, xhr, formData) {
            // Append csrf token
            formData.append('_token', csrfToken);

            // Append external fields (if needed)
            var externalFields = {};
            var currentFolder = $(".breadcrumb-folders li.current");
            if (currentFolder.length && (typeof getFolderParents != undefined) && $.isFunction(getFolderParents)) {
                var parents = getFolderParents(currentFolder);
                parents.each(function () {
                    externalFields[$(this).attr('data-instance')] = $(this).attr('data-key');
                });
            }
            formData.append('externalFields', JSON.stringify(externalFields));

            // Append elements inside of dorpzone element self
            var inElementData = $(this.element).find(':input').serializeArray();
            $.each(inElementData, function (index, node) {
                formData.append(node.name, node.value);
            });

            // Show File Progress Info
            var fileInfoEl = $('<div class="media-uploader-status"></div>');

            fileInfoEl.append('<button type="button" class="fa fa-times-circle upload-dismiss"></button>');
            fileInfoEl.append('<h2 class="media-uploader-status-text">' + messages.statuses.uploading + '</h2>');
            fileInfoEl.append('<div class="media-progress"><div class="media-progress-value"></div></div>');
            fileInfoEl.append('<div class="upload-detail"><div class="upload-filename"></div></div>');
            fileInfoEl.find('.upload-filename').html(file.name);

            $('.files-uploading-status').append(fileInfoEl);
            file.uploadResultElement = fileInfoEl;
        },

        uploadprogress: function (file, progress, bytesSent) {
            file.uploadResultElement.find('.media-progress-value').width(progress + '%');
        },

        error: function (file, response, xhr) {
            $(file.previewElement).find('.dz-error-message').remove();
            if (xhr.status == 422) {
                var uploadResultElement = file.uploadResultElement;
                var errorsContainer = $('<div class="upload-errors"></div>');
                var ul = $('<ul></ul>');
                if (response.file) {
                    $.each(response.file, function (index, error) {
                        ul.append($('<li></li>').html(pd(error)));
                    });
                }
                errorsContainer.append(ul);
                uploadResultElement.append(errorsContainer);

                uploadResultElement.addClass('error');
                uploadResultElement.find('.media-uploader-status-text').html(messages.statuses.failed);
            }
        },

        success: function (file, response) {
            var uploadResultElement = file.uploadResultElement;
            uploadResultElement.find('.media-uploader-status-text').html(messages.statuses.success);
        },
    }
};


function updateTarget(dropzoneInstance, target) {
    if (dropzoneInstance.getUploadingFiles().length === 0 && dropzoneInstance.getQueuedFiles().length === 0) {
        var accepted = dropzoneInstance.getAcceptedFiles();
        var dataArr = [];
        var targetEl = $('#' + target);
        $.each(accepted, function (index, file) {
            if (file.status == "success") {
                var rsJson = $.parseJSON(file.xhr.response);
                dataArr.push(rsJson.file);
            }
        });
        if (dataArr.length) {
            targetEl.val(JSON.stringify(dataArr));
        } else {
            targetEl.val('');
        }
    }
}

function removeFromServer(file, dropzoneElement) {
    if (file.xhr) {
        var rs = $.parseJSON(file.xhr.response);
        var data = rs;
        data._token = csrfToken;

        var additionalData = dropzoneElement.find(':input').serializeArray();
        $.each(additionalData, function (index, item) {
            data[item.name] = item.value;
        });
        $.ajax({
            url: dropzoneRoutes.remove,
            type: 'POST',
            data: data,
        })
    }
}

function refreshDropzone(dropzoneObj) {
    dropzoneObj.removeAllFiles();
    var varName = $(dropzoneObj.element).attr('data-var-name');
    $('.files-uploading-status[data-var-name="' + varName + '"]').html('');
}