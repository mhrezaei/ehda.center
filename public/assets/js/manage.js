/**
 * Created by jafar on 7/6/2016 AD.
 */
$(document).ready(function () {
	sidebarInitiate();

	$("#divSideMother").css('height', $(window).height() - 50);
	$(window).resize(function () {
		$("#divSideMother").css('height', $(window).height() - 50);
	});

// 	heyCheck() ;
});

function rowHide($table_id, $model_id) {
	if ($table_id == 'auto')
		var $table_selector = ' .tableGrid';
	else
		var $table_selector = '#' + $table_id;

	var $row_selector = $table_selector + ' #tr-' + $model_id;
	$($row_selector).slideUp();
	tabReload();
}

function heyCheck() {
	// Last Run...
	in_server = false;
	now = new Date();
	heyChecked = parseInt(localStorage.getItem('heyChecked'));
	if (now.getTime() - heyChecked > 1000 * 60 * 10) {
		in_server = true;
		localStorage.setItem('heyChecked', now.getTime());
	}

	// Check Process
	if (in_server) {
		$.ajax({
			url     : url('manage/heyCheck'),
			dataType: "json",
			cache   : false
		})
			.done(function (result) {
				forms_log(result);
				localStorage.setItem('heyCheck', result.ok);
			});
	}

	// Action...
	if (localStorage.getItem('heyCheck') == 'false') {
		loginAlert('on');
	}
	else {
		loginAlert('off');
	}

	setTimeout('heyCheck()', 1000);
// 	forms_log( "heyCheck #" + localStorage.getItem('heyChecked') + "; in_server:" + in_server + "; result: " + localStorage.getItem('heyCheck') );
}

function loginAlert(mood) {
	$alert = $('#divHeyCheck');
	if (mood == 'on') {
		$alert.fadeIn('fast');
	}
	else {
		$alert.fadeOut('fast');
	}
}

function rowUpdate($table_id, $model_id) {
	if ($table_id == 'auto')
		var $table_selector = ' .tableGrid';
	else
		var $table_selector = '#' + $table_id;

	if ($model_id == '0') {
		if ($($table_selector).length) {
			forms_delaiedPageRefresh(1);
		}
	}
	else {
		var $row_selector = $table_selector + ' #tr-' + $model_id;
		var $url = $($row_selector + ' .refresh ').html();
		var $counter = $($row_selector + ' .-rowCounter ').html();
		$($row_selector).addClass('loading');
		$.ajax({
			url  : $url,
			cache: false,
		})
			.done(function (html) {
				$($row_selector).html(html);
				$($row_selector).removeClass('loading');
				$($row_selector + ' .-rowCounter ').html($counter);
			});
	}
}

function tabReload() {
	var url = $("#divTab .refresh").html();
	var $tab_div = $("#divTab");

	if (!url) {
		return;
	}

	$tab_div.addClass('loading');

	forms_log(url);
	$.ajax({
		url  : url,
		cache: false
	})
		.done(function (html) {
			$tab_div.html(html);
			$tab_div.removeClass('loading');
		});
}

function divReload(div_id) {
	var $div = $("#" + div_id);
	var reload_url = $("#" + div_id + " .refresh").html();

	if (!reload_url) {
		reload_url = $div.attr('data-src') ;
		reload_url = reload_url.replaceAll("-id-" , $div.attr('data-id')) ;
		reload_url = url(reload_url);
	}
	if(!reload_url) {
		return ;
	}


	$div.addClass('loading');
	forms_log(reload_url);
	$.ajax({
		url  : reload_url,
		cache: false
	}).done(function (html) {
		$div.html(html);
		$div.removeClass('loading');
	});
}

function masterModal($url, $size) {
	//Preparetions...
	if (!$size) $size = 'lg';
	var $modal_selector = '#masterModal-' + $size;

	//Form Load...
	$($modal_selector + ' .modal-content').html('<div class="modal-wait">...</div>').load($url, function () {
		$('.selectpicker').selectpicker();
	});
	$($modal_selector).modal();


}
function modalForm($modal_id, $item_id, $parent_id) {
	//Preparetions...
	if (!$parent_id) $parent_id = '0';
	var $modal_selector = '#' + $modal_id;
	var $form_selector = $modal_selector + ' form ';
//	var $url = $($form_selector+'._0').html().replace('-id-',$item_id).replace('-parent-',$parent_id);

	//Form Placement...
//	if($item_id=='0')
//		$($modal_selector + '-title').html($($form_selector+'._2').html());
//	else
//		$($modal_selector + '-title').html($($form_selector+'._1').html());

	//Form Load...
//	$($form_selector + 'div.modal-body').html('....').load($url , function() {
//		$('.selectpicker').selectpicker();
//	});
	$($modal_selector).modal();

}


function search($form_id) {
	var $input = $('#' + $form_id + ' input[name=key]');
	var $key = $input.val();
	var $url = $('#' + $form_id).attr('action').replace('-key-', $key);

	if (!$key) return false;
	window.location = $url;
	return false;
}

function gridSelector($mood, $id) {
	switch ($mood) {
		case 'tr' :
			$('#gridSelector-' + $id).prop('checked', !$('#gridSelector-' + $id).is(":checked"));

		case 'selector' :
			if ($('#gridSelector-' + $id).is(":checked"))
				$('#tr-' + $id).addClass('warning');
			else
				$('#tr-' + $id).removeClass('warning');
			gridSelector('buttonActivator');
			break;

		case 'all' :
			if ($('#gridSelector-all').is(':checked')) {
				$('.gridSelector').prop('checked', true);
				$('tr.grid').addClass('warning');
			}
			else {
				$('.gridSelector').prop('checked', false);
				$('tr.grid').removeClass('warning');
			}
			gridSelector('buttonActivator');
			break;

		case 'count':
			var $count = 0;
			$(".gridSelector:checked").each(function () {
				$count++;
			});
			return $count;

		case 'get' :
			var $list = '';
			var $count = 0;
			$(".gridSelector:checked").each(function () {
				$id = $(this).attr('data-value');
				$list += $id + ',';
				$count++;
			});
			$('input[name=ids]').val($list);
			$('#txtCount').val(forms_pd($count + ' مورد '));
			break;

		case 'buttonActivator' :
			if (gridSelector('count') > 0)
				$('#action0').prop('disabled', false);
			else
				$('#action0').prop('disabled', true);
	}
}

function posttypeFeatures($feature) {
	var $button = $("#lblFeature-" + $feature);
	var $input = $("#txtFeatures");
	var $meta = $("#txtMeta");
	var $fields_array = available_features[$feature][2];
	var $fields = '';

	$fields_array.forEach(function ($item) {
		$fields += $item + ", ";
	});

	forms_log($fields);

	if ($input.val().indexOf($feature) >= 0) {
		$input.val($input.val().replaceAll($feature, ''));
		$button.css('opacity', '0.5');
		$meta.val($meta.val().replaceAll($fields, ''));
	}
	else {
		$input.val($input.val() + ' ' + $feature + ' ');
		$button.css('opacity', '0.9');
		$meta.val($meta.val() + $fields);
	}

	/*-----------------------------------------------
	 | Features that must be together ...
	 */
	if ($feature == 'full_history' && $input.val().indexOf('full_history') >= 0 && $input.val().indexOf('history_system') < 0) {
		posttypeFeatures('history_system');
	}
	if ($feature == 'history_system' && $input.val().indexOf('full_history') >= 0 && $input.val().indexOf('history_system') < 0) {
		posttypeFeatures('full_history');
	}

	if ($feature == 'basket' && $input.val().indexOf('basket') >= 0 && $input.val().indexOf('price') < 0) {
		posttypeFeatures('price');
	}
	if ($feature == 'price' && $input.val().indexOf('basket') >= 0 && $input.val().indexOf('price') < 0) {
		posttypeFeatures('basket');
	}


}


function downstreamPhotoSelected($input_selector) {
	$($input_selector).val($($input_selector).val().replace(url(), ''));
}

function downstreamPhotoPreview($input_selector) {
	$url = $($input_selector).val();
	if ($url)
		window.open(url($url));
}


function sidebarToggle($speed) {
	if (!$speed) $speed = 0;
	$current_sitation = localStorage.getItem('sidebar');
	if (!$current_sitation) $current_sitation = "shown";

	if ($current_sitation == "shown") {
		//hide command:
		$(".sidebar").hide();
		$("#sidebarHandle").removeClass('fa-chevron-right').addClass('fa-chevron-left');
		localStorage.setItem('sidebar', 'hidden');
		$("#page-wrapper").animate({
			"margin-right": 0,
		}, $speed);
	}
	else {
		//show command:
		$("#page-wrapper").animate({
			"margin-right": 200,
		}, $speed, function () {
			$(".sidebar").show();
			$("#sidebarHandle").removeClass('fa-chevron-left').addClass('fa-chevron-right');
		});
		localStorage.setItem('sidebar', 'shown');
	}

	return localStorage.getItem('sidebar');
}

function sidebarInitiate() {
	$current_sitation = localStorage.getItem('sidebar');
	if ($current_sitation == 'hidden') {
		localStorage.setItem('sidebar', 'shown');
		return sidebarToggle(0);
	}
}

function drawingProgress(now_processed) {
	//Hide/Show Elements...
	var $bar = $('#divProgress');
	$('.-progressHide').parent().hide();
	$bar.parent().show();

	//Progress Effect...
	var current_value = parseInt($bar.attr('aria-valuenow'));
	var total_numbers = parseInt($bar.attr('aria-valuemax'));
	var new_value = current_value + now_processed;
	var percent = (new_value * 100 / total_numbers);
	if (percent > 100) percent = 100;
	$bar.attr('aria-valuenow', new_value).css('width', percent.toString() + "%");

	//Next Stage...
	$("#btnPrepare").click();
}

function drawingRandom(max) {
	var $input = $("#txtDrawingGuess");

	for ($i = 1; $i < 100; $i++) {
		setTimeout(function () {
			var random_number = Math.floor(Math.random() * (max)) + 1;
			$input.val(forms_pd(random_number.toString()));
		}, 10 * $i);
	}
	setTimeout(function () {
		$("#btnSubmit").click();
	}, 11 * $i);

}

function drawingDelete(key_number, post_id) {
	$.ajax({
		url  : url("manage/club/save/draw_delete/" + key_number),
		cache: false
	})
		.done(function (html) {
			divReload('divWinnersTable');
			rowUpdate('tblPosts', post_id)
		});
}

/**
 * used in "roles.one.blade"
 *
 * @param role_id
 * @returns {null}
 */
function roleAttachmentEffect(role_id) {
	var new_status = $("#cmbStatus-" + role_id).val();
	var $button = $("#btnRoleSave-" + role_id);
	$button.removeClass('btn-warning btn-primary btn-danger');

	switch (new_status) {
		case 'ban' :
			$button.addClass('btn-warning');
			break;
		case 'detach' :
			$button.addClass('btn-danger');
			break;
		default :
			$button.addClass('btn-primary');
			break;
	}
	$button.fadeIn('fast');

	return null;
}

/**
 * used in "roles.one.blade"
 *
 * @param user_id
 * @param role_id
 * @param role_slug
 */
function roleAttachmentSave(user_id, role_id, role_slug) {
	var new_status = $("#cmbStatus-" + role_id).val();
	var $button = $("#btnRoleSave-" + role_id);

	$.ajax({
		url     : url('manage/users/save/role/' + user_id + '/' + role_slug + '/' + new_status),
		dataType: "json",
		cache   : false
	})
		.done(function (result) {
			divReload("divRole-" + role_id);
			rowUpdate('tblUsers', user_id);
		});

	return null;

}

function permitClick($this, new_value) {
	var clicked_on = $this.attr('for');

	//Find Out new_value ...
	var current_value = $this.attr('value');
	switch (new_value) {
		case '0' :
			new_value = 0;
			break;
		case '1' :
			new_value = 2;
			break;
		case '2' :
			new_value = 2;
			break;
		default :
			if (current_value == '2') {
				new_value = 0;
			}
			else {
				new_value = 2;
			}

	}

	//Action if clicked on a locale...
	if (clicked_on == 'locale') {
		permitUpdate($this.attr('checker'), new_value);
	}

	//Action if clicked on a permit without locale...
	if (clicked_on == 'permit' && $this.attr('hasLocale') == '0') {
		permitUpdate($this.attr('checker'), new_value);
	}

	//Action if clicked on a permit with locales...
	if (clicked_on == 'permit' && $this.attr('hasLocale') == '1') {
		$(".-" + $this.attr('module') + "-" + $this.attr('permit') + "-locale").each(function () {
			permitUpdate($(this).attr('checker'), new_value);
		});
	}

	//Action if clicked on a module...
	if (clicked_on == 'module') {
		$(".-" + $this.attr('module') + "-permit").each(function () {
			permitClick($(this), new_value.toString());
		});
	}

	//Spread...
	permitSpread();
//		forms_log(clicked_on);

}

function permitUpdate(string, new_value) {
	var $input = $('#txtPermissions');

	//Add...
	if (new_value > 0) {
		var permission = $input.val();

		if (permission.search(string) < 0) {
			permission = permission + " " + string;
			$input.val(permission);
		}

	}

	//Remove...
	else {
		$input.val($input.val().replaceAll(string, ''));

	}
}

function permitSpread() {
	var permission = $('#txtPermissions').val();

	var icon_checked = "fa-check-circle-o";
	var icon_unchecked = "fa-circle-o";
	var icon_semichecked = "fa-dot-circle-o";

	var text_checked = "text-success";
	var text_unchecked = "text-darkgray";
	var text_semichecked = "text-violet";

	//Reset all links...
	$('.-permit-link').removeClass(text_checked).removeClass(text_unchecked).removeClass(text_semichecked).children('.fa').removeClass(icon_checked).removeClass(icon_unchecked).removeClass(icon_semichecked);

	//‌‌Spread check marks...
	$(".-module").each(function () {
			var module = $(this).attr('module');
			var counter = 0;
			var checked = 0;

			$(".-" + module + "-permit").each(function () {
					var permit = $(this).attr('permit');
					var counter2 = 0;
					var checked2 = 0;

					//When Has Locales...
					if ($(this).attr('hasLocale') == 1) {

						$(".-" + module + "-" + permit + "-locale").each(function () {
							var locale = $(this).attr('locale');
							counter++;
							counter2++;

							if (permission.search(module + "." + permit + "." + locale) >= 0) {
								$(this).children('.-locale-handle').addClass(icon_checked);
								$(this).addClass(text_checked).attr('value', '2');
								checked++;
								checked2++;
							}
							else {
								$(this).children('.-locale-handle').addClass(icon_unchecked);
								$(this).addClass(text_unchecked).attr('value', '0');
							}

						});

						// Permissions:
						if (checked2 == counter2) {
							$(this).children('.-permit-handle').addClass(icon_checked);
							$(this).addClass(text_checked).attr('value', '2');
						}
						else if (checked2 == 0) {
							$(this).children('.-permit-handle').addClass(icon_unchecked);
							$(this).addClass(text_unchecked).attr('value', '1');
						}
						else {
							$(this).children('.-permit-handle').addClass(icon_semichecked);
							$(this).addClass(text_semichecked).attr('value', '0');
						}

					}
					//When Doesn't have locales...
					else {
						counter++;
						if (permission.search(module + "." + permit) >= 0) {
							$(this).children('.-permit-handle').addClass(icon_checked);
							$(this).addClass(text_checked).attr('value', '2');
							checked++;
						}
						else {
							$(this).children('.-permit-handle').addClass(icon_unchecked);
							$(this).addClass(text_unchecked).attr('value', '0');
						}
					}
				}
			);


			// Module:
			if (checked == counter) {
				$(this).children('.-module-handle').addClass(icon_checked);
				$(this).addClass(text_checked).attr('value', '2');
			}
			else if (checked == 0) {
				$(this).children('.-module-handle').addClass(icon_unchecked);
				$(this).addClass(text_unchecked).attr('value', '1');
			}
			else {
				$(this).children('.-module-handle').addClass(icon_semichecked);
				$(this).addClass(text_semichecked).attr('value', '0');
			}

		}
	)
	;

}

function cardEditor($mood , $para='')
{
	$divCard = $('#divCard') ;
	$divCard.slideUp('fast') ;

	switch($mood) {
		case 1 :
			$('#divInquiry,#divForm').slideToggle('fast');
			$('#frmEditor [name=code_melli]').val( $('#txtInquiry').val() ) ;
			$('#frmEditor [name=gender]').focus() ;
			break;

		case 2:
			$divCard.attr('data-id' , $para);
			divReload('divCard');
//			$('#imgCard').attr('src' , url('/card/show_card/mini/'+$para));
//			$('#txtCard').val( $para );
			$divCard.slideDown('fast');
			break;
	}

}


