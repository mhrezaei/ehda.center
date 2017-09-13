/**
 * Created by EmiTis on 12/08/2017.
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

// Temporary Elements
let fileInfoElTmp = $('<div class="media-uploader-status"> <div class="col-xs-9"> <div class="media-uploader-status-info"> <h2 class="media-uploader-status-text"> </h2> <button type="button" class="fa fa-times-circle delete-upload-info"></button> <div class="media-progress"> <div class="media-progress-value"></div> </div> <div class="upload-detail"> <span class="upload-detail-separator"></span> <span class="upload-filename"></span> </div> </div> </div> <div class="col-xs-3"> <div class="attachment media-uploader-status-image"> <img> </div> </div></div>');

// Temporary Variables
let maxQueueLength = 40;

// dropzone Options
var dropzoneOptions = {
    init: {
        sending: function (file, xhr, formData) {
            // Append csrf token
            formData.append('_token', csrfToken);

            // Hide element in dropzone
            $(file.previewElement).hide();
            $(file.previewElement).closest('.uploader-container').find('.dz-message').show();

            // Append elements inside of dorpzone element self
            var inElementData = $(this.element).find(':input').serializeArray();
            $.each(inElementData, function (index, node) {
                formData.append(node.name, node.value);
            });

            // Append external fields (if needed)
            let currentExternalFields = $(this.element).find('#externalFields');
            let externalFields = currentExternalFields.length ? $.parseJSON(currentExternalFields.val()) : {};
            formData.append('externalFields', JSON.stringify(externalFields));


            // Show File Progress Info
            var fileInfoEl = fileInfoElTmp.clone();

            fileInfoEl.find('.media-uploader-status-text').html(messages.statuses.uploading);
            fileInfoEl.find('.upload-filename').html(file.name);
            var fileInfoImg = fileInfoEl.find('.media-uploader-status-image').children('img');
            fileInfoImg.attr('src', dropzoneRoutes.images + '/template/file-o.svg');

            var image = new Image();
            image.onload = function (e) {
                fileInfoImg.attr('src', image.src)
            };
            image.src = URL.createObjectURL(file);

            $('.files-uploading-status').append(fileInfoEl);
            file.uploadResultElement = fileInfoEl;
        },

        uploadprogress: function (file, progress, bytesSent) {
            if (progress < 100) {
                file.uploadResultElement.find('.media-progress-value').width(progress + '%');
            }
        },

        complete: function (file) {
            file.uploadResultElement.find('.media-progress-value').width('100%');
        },

        error: function (file, response, xhr) {
            $(file.previewElement).find('.dz-error-message').remove();
            let uploadResultElement = file.uploadResultElement.find('.media-uploader-status-info');

            if (xhr.status == 422) {
                let errorsContainer = $('<div class="upload-errors"></div>');
                let ul = $('<ul></ul>');
                if (response.file) {
                    $.each(response.file, function (index, error) {
                        ul.append($('<li></li>').html(pd(error)));
                    });
                }
                errorsContainer.append(ul);
                uploadResultElement.append(errorsContainer);

            }

            uploadResultElement.addClass('error');
            uploadResultElement.find('.media-uploader-status-text').html(messages.statuses.failed);
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

function removeFile(file, dropzoneElement) {
    var uploadResultElement = file.uploadResultElement;
    if (uploadResultElement && uploadResultElement.length) {
        uploadResultElement.remove();
    }
    // removeFromServer(file, dropzoneElement);
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
}

$(document).ready(function () {
    $(document).on({
        click: function () {
            let row = $(this).closest('.media-uploader-status');
            if (row.length) {
                row.remove();
            }
        }
    }, '.delete-upload-info')
});