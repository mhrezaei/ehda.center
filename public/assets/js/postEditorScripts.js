/*
 |--------------------------------------------------------------------------
 | On Load ...
 |--------------------------------------------------------------------------
 |
 */

function postsInit() {
    postDomainToggle();
}

/*
 |--------------------------------------------------------------------------
 | Helpers
 |--------------------------------------------------------------------------
 |
 */

function postToggleCategories(count) {
    if (count > 0) {
        $("#divCategories").slideDown();
    }
}
function postTogglePrice() {
    $('#btnSale,#divSale').slideToggle('fast', function () {
        if ($("#divSale").is(':visible')) {
            $("#txtSalePrice").focus();
        }
        else {
            $(".salePrice").val('');
        }
    });
}
function postCalcSale(called_from) {
    var $price = $("#txtPrice");
    var $percent = $("#txtSalePercent");
    var $sale = $("#txtSalePrice");

    var price_val = parseInt(ed($price.val().replaceAll(',', '')));
    var sale_val = parseInt(ed($sale.val()).replaceAll(',', ''));
    var percent_val = parseInt(ed($percent.val()).replaceAll(',', ''));

    if (called_from == 'price') {
        called_from = 'sale';
    }
    if (called_from == 'sale') {
        if (!price_val || price_val == 0 || !sale_val || sale_val == 0 || sale_val > price_val) {
            $percent.val('');
        }
        else {
            var result = Math.round((price_val - sale_val) * 100 / price_val);
            $percent.val(pd(result.toString()));
        }
    }
    if (called_from == 'percent') {
        if (!price_val || price_val == 0 || !percent_val || percent_val == 0 || percent_val > 100) {
            $sale.val('');
        }
        else {
            var result = Math.round((100 - percent_val) * price_val / 100);
            $sale.val(pd(addCommas(result.toString())));
        }
    }
}

function postToggleTitle2() {
    var $txtTitle2 = $('#txtTitle2');

    $('#lblTitle2,#txtTitle2-container').toggle();
    if ($txtTitle2.is(':visible')) {
        $txtTitle2.focus();
    }
    else {
        $txtTitle2.val('');
    }
}

function postToggleSchedule($mood) {
    var $schedule = $("#divSchedule");
    var $link = $("#lnkSchedule");
    var $date = $("#txtPublishDate");
    var $flag = $("#txtScheduleFlag");

    if (!$mood) {
        forms_log("mood=" + $mood);
        if ($($schedule).is(':visible'))
            $mood = 'hide';
        else
            $mood = 'show';
    }

    switch ($mood) {
        case 'show' :
            $schedule.slideDown('fast');
            $flag.val('1');
            $link.hide();
            $date.focus();
            break;

        case 'hide' :
            $schedule.slideUp('fast');
            $link.show();
            $date.val('');
            $flag.val('');
            $("#cmbPublishDate").val('08:00');
            $('.selectpicker').selectpicker('refresh');
            break;

    }

    return $mood;

}

function postsAction($command, $model_id) {
    forms_log('action: ' + $command);
    switch ($command) {
        case 'adjust_publish_time' :
            postToggleSchedule('show');
            break;

        case 'refer_back' :
            modalForm("modalPostReject", '1');
            break;

        case 'submit_reject': //called from #modalPostReject
            $(".modal").modal("hide");
            $("#txtModerateNote").val($("#txtModerateNote2").val());
            $('#btnReject').click();
            break;

        case 'delete':
            modalForm("modalPostDeleteWarning", '1');
            break;

        case 'unpublish':
            modalForm("modalPostUnpublishWarning", '1');
            break;

        case 'send_for_approval' :
            $("#btnApproval").click();
            break;

        case 'check_slug' :
            $divFeedback = $("#divSlugFeedback");
            $divFeedback.html('...').addClass('loading');

            forms_log('[' + $("#txtSlug").val() + ']');
            $.ajax({
                url: url("manage/posts/check_slug/" + $("#txtId").val() + "/" + $("#txtType").val() + "/" + $("#txtLocale").val() + '' + '/' + $("#txtSlug").val() + ' /' + 'p'),
                cache: false
            })
                .done(function (html) {
                    $($divFeedback).html(html);
                    $($divFeedback).removeClass('loading');
                });

            break;

        case 'refer_to' :
            masterModal(url('manage/posts/act/' + $model_id + '/owner/1'));
            break;

    }
}

function featuredImage(event) {
    switch (event) {
        case 'inserted' :
            $('#divFeaturedImage').slideDown();
            $('#btnFeaturedImage').addClass('btn-default').removeClass('btn-primary');
            break;

        case 'deleted' :
            $('#divFeaturedImage').slideUp('fast');
            $('#txtFeaturedImage').val('');
            $('#imgFeaturedImage').attr('src', '');
            $('#btnFeaturedImage').addClass('btn-primary').removeClass('btn-default');
            break;

    }
}

function postPhotoAdded() {
    var $src = url($('#txtAddPhoto').val());
    var $new_div = $('#divNewPhoto').html();
    var $counter_label = $('#spnPhotoCount');
    var $counter_input = $('#txtLastKey');
    var $new_key = parseInt($counter_input.val()) + 1;
    var $new_selector = '#divPhoto-' + $new_key.toString();
    var $new_counter = parseInt(forms_digit_en($counter_label.html())) + 1;

    $counter_input.val($new_key);
    $counter_label.html(forms_digit_fa($new_counter.toString()));

    $new_div = $new_div.replace('NEW', $new_key);
    $new_div = $new_div.replace('NEW', $new_key);
    $new_div = $new_div.replace('NEW', $new_key);
    $new_div = $new_div.replace('NEW', $new_key);

    $('#divPhotos').append($new_div);
    $($new_selector + ' input.-src').val($src);
    $($new_selector + ' input.-label').focus();
    $($new_selector + ' img').attr('src', $src);
    $($new_selector).slideDown();

}

function postPhotoRemoved(key, hashid) {
    let div_id = "divPhoto-" + key;

    $('#' + div_id).attr('data-src', 'manage/posts/act/0/editor-album2-remove/' + hashid).slideUp('fast');
    divReload(div_id);

}
function _postPhotoRemoved($selector) {

    $selector.parent().parent().slideUp().html('');

    var $counter_label = $('#spnPhotoCount');
    var $new_counter = parseInt(forms_digit_en($counter_label.html())) - 1;
    $counter_label.html(forms_digit_fa($new_counter.toString()));

}

function postFileCounterUpdate(update) {
    let $selector = $('#spnFileCount');
    let current_value = parseInt(forms_digit_en($selector.html()));

    forms_log(current_value);

    if (update == '-') {
        $selector.html(forms_digit_fa(Math.max(0, current_value - 1).toString()));
    }
    else if (update == '+') {
        $selector.html(forms_digit_fa((current_value + 1).toString()));
    }
    else {
        $selector.html(forms_digit_fa(update));
    }
}

function postFormChange() {
    var $flag = $('#txtChangeWarning');
    if ($flag.val() == '1') {
        modalForm('modalSuggestCopy', 1);
        $flag.val('0');
    }
    $('#txtChangeDetected').val('1');
}

function postDomainToggle() {
    combo_value = $("#cmbDomain").val();
    $checkbox = $("#chkReflectInGlobal");
    if (combo_value == 'global') {
        $checkbox.hide();
    }
    else {
        $checkbox.slideDown('fast');
    }
}

function filemanagerUploadFinish(uploaded_files) {

    let $last_key_input = $("#txtLastKey");

    while (uploaded_files.length) {
        let counter = 0;
        let result = $last_key_input.val() + "-";
        let current = uploaded_files.splice(0, 50);
        $.each(current, function (index, item) {
            result += $.parseJSON(item.xhr.response).file + "-";
            counter++;
        });

        $last_key_input.val(parseInt($last_key_input.val()) + counter);

        divReload('divNewFiles', result);
    }

    refreshDropzone(dropzone_object);
}
