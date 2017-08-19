/**
 * Created by Yasna-PC1 on 16/08/2017.
 */
(function ($) {

    /**
     * Sets event listener on a button to opern file-manager-modal
     * - This function reads some options from data attributes.
     * - These data attributes should start with "file-manager"
     * - Available Options:
     * -- "input": ID attribute of target element that will hold the result.
     * -- "preview": ID attribute of element that will holds preview of selected file.
     * -- "callback": Some JS Codes that will be run after choosing file.
     * -- "output-type": Specifies how to return result. (url, pathname...)
     *
     * @param {string} type
     * @param {object} options
     * @returns {jQuery}
     */
    $.fn.fileManagerModal = function (type, options) {
        type = type || 'image';

        if (type === 'image' || type === 'images') {
            type = 'Images';
        } else {
            type = 'Files';
        }

        this.on('click', function (e) {
            let that = $(this);
            let route_prefix = (options && options.prefix) ? options.prefix : '/file-manager';
            let fileManagerOptions = {
                modal: true,
            };
            let data = that.dataStartsWith('file-manager');
            fileManagerOptions = $.extend(fileManagerOptions, data);

            // if (fileManagerOptions) {
            //     var route = route_prefix + '?' + queryString;
            // } else {
            var route = route_prefix;
            // }

            window.fileManagerModalOptions = fileManagerOptions;

            // localStorage.setItem('target_input', $(this).data('input'));
            // localStorage.setItem('target_preview', $(this).data('preview'));
            $("#file-manager-modal").find('.file-manager-iframe').attr('src', route);
            $("#file-manager-modal").modal()
            // window.open(route_prefix + '?type=' + type, 'FileManager', 'width=900,height=600');
            return false;
        });

        return this;
    }

})(jQuery);

function encodeQueryData(data) {
    let ret = [];
    for (let d in data)
        ret.push(encodeURIComponent(d) + '=' + encodeURIComponent(data[d]));
    return ret.join('&');
}

function closeFileManagerModal() {
    $('#file-manager-modal').modal('hide')
}

function SetUrl(url, file_path) {
    //set the value of the desired input to image url
    var target_input = $('#' + localStorage.getItem('target_input'));
    target_input.val(file_path);

    //set or change the preview image src
    var target_preview = $('#' + localStorage.getItem('target_preview'));
    target_preview.attr('src', url);

    setTimeout(localStorage.getItem('callback'), 1)

}
