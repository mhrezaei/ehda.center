/**
 * Created by jafar on 7/6/2016 AD.
 */
$( document ).ready( function() {
	sidebarInitiate();
});

function rowHide($table_id , $model_id)
{
	if($table_id == 'auto')
		var $table_selector = ' .tableGrid'  ;
	else
		var $table_selector = '#'+$table_id ;

	var $row_selector = $table_selector + ' #tr-' + $model_id ;
	$($row_selector).slideUp() ;
	tabReload();
}

function rowUpdate($table_id , $model_id)
{
	if($table_id == 'auto')
		var $table_selector = ' .tableGrid'  ;
	else
		var $table_selector = '#'+$table_id ;

	if($model_id=='0') {
		if($($table_selector).length) {
			forms_delaiedPageRefresh(1);
		}
	}
	else {
		var $row_selector = $table_selector + ' #tr-' + $model_id ;
		var $url = $($row_selector+' .refresh ').html() ;
		var $counter = $($row_selector + ' .-rowCounter ').html() ;
		$($row_selector).addClass('loading') ;
		$.ajax({
			url: $url,
			cache: false ,
		})
		.done(function (html) {
			$($row_selector).html(html);
			$($row_selector).removeClass('loading') ;
			$($row_selector + ' .-rowCounter ').html($counter) ;
		});
	}
}

function tabReload()
{
	$("#divTab").addClass('loading');

	forms_log($("#divTab .refresh").html() );
	$.ajax({
		url:$("#divTab .refresh").html() ,
		cache: false
	})
	.done(function(html) {
		$("#divTab").html(html);
		$("#divTab").removeClass('loading');
	});
}
function masterModal($url,$size)
{
	//Preparetions...
	if(!$size) $size = 'lg' ;
	var $modal_selector = '#masterModal-' + $size ;

	//Form Load...
	$($modal_selector + ' .modal-content').html('<div class="modal-wait">...</div>').load($url , function() {
		$('.selectpicker').selectpicker();
	});
	$($modal_selector).modal() ;


}
function modalForm($modal_id , $item_id , $parent_id)
{
	//Preparetions...
	if(!$parent_id) $parent_id='0' ;
	var $modal_selector = '#' + $modal_id ;
	var $form_selector = $modal_selector + ' form ' ;
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
	$($modal_selector).modal() ;

}


function postSave($action)
{
	var $form_selector = '#frmEditor' ;
	$('#txtAction').val($action) ;
	tinyMCE.triggerSave();
	$($form_selector).submit() ;
}

function postChange($action)
{
	var $form_selector = '#frmEditor' ;
	var $id = $($form_selector+' [name=id] ').val();
	var $url = url('manage/posts/'+$id+'/'+$action);

	$($form_selector + ' .form-feed').html('<div class="modal-wait">...</div>').load($url , function() {
		forms_delaiedPageRefresh(1);
	}).slideDown('fast');
}

function search($form_id)
{
	var $input = $('#'+$form_id+ ' input[name=key]');
	var $key   = $input.val() ;
	var $url   = $('#'+$form_id).attr('action').replace('-key-',$key);

	if(!$key) return false ;
	window.location = $url ;
	return false ;
}

function gridSelector($mood , $id)
{
	switch($mood) {
		case 'tr' :
			$('#gridSelector-'+$id).prop('checked', !$('#gridSelector-'+$id).is(":checked"));

		case 'selector' :
			if ($('#gridSelector-'+$id).is(":checked"))
				$('#tr-'+$id).addClass('warning');
			else
				$('#tr-'+$id).removeClass('warning');
			gridSelector('buttonActivator');
			break;

		case 'all' :
			if($('#gridSelector-all').is(':checked')) {
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
			var $count = 0 ;
			$(".gridSelector:checked").each(function () {
				$count++ ;
			});
			return $count ;

		case 'get' :
			var $list = '';
			var $count = 0 ;
			$(".gridSelector:checked").each(function () {
				$id = $(this).attr('data-value');
				$list += $id+',';
				$count++ ;
			});
			$('input[name=ids]').val($list);
			$('#txtCount').val(forms_pd($count + ' مورد '));
			break ;

		case 'buttonActivator' :
			if(gridSelector('count')>0)
				$('#action0').prop('disabled', false);
			else
				$('#action0').prop('disabled', true);
	}
}

function posttypeFeatures($feature)
{
	var $button = $("#lblFeature-"+$feature) ;
	var $input = $("#txtFeatures");
	var $meta = $("#txtMeta");
	var $fields_array = available_features[$feature][2];
	var $fields = '' ;

	$fields_array.forEach( function($item) {
		$fields += $item + ", " ;
	});

	forms_log($fields);

	if($input.val().indexOf($feature)>=0){
		$input.val($input.val().replaceAll($feature , ''));
		$button.css('opacity','0.3');
		$meta.val( $meta.val().replaceAll($fields , '') ) ;
	}
	else {
		$input.val($input.val() + ' ' + $feature + ' ');
		$button.css('opacity','0.9');
		$meta.val( $meta.val() + $fields ) ;
	}
//	forms_log($input.val());
}

function postEditorFeatures($special_action)
{
	if(!$special_action) {
		$special_action = null ;
	}

	switch( $special_action) {
		case 'featured_image_inserted' :
			$('#divFeaturedImage').slideDown() ;
			$('#btnFeaturedImage').addClass('btn-default').removeClass('btn-primary');
			break;

		case 'featured_image_deleted' :
			$('#divFeaturedImage').slideUp('fast') ;
			$('#txtFeaturedImage').val('');
			$('#imgFeaturedImage').attr('src','');
			$('#btnFeaturedImage').addClass('btn-primary').removeClass('btn-default');
			break;

		default :
			//Domain Selector...
			if($('#cmbDomain').val()=='global')
				$('#chkGlobal').hide();
			else
				$('#chkGlobal').show();

			//PublishDate Selector...
			if($('#cmbPublishDate').val()=='auto')
				$('#txtPublishDate').parent().hide();
			else
				$('#txtPublishDate').parent().show();
			break;
	}
}


function postPhotoAdded()
{
	let $src = $('#txtAddPhoto').val() ;
	let $new_div = $('#divNewPhoto').html();
	let $counter_label = $('#spnPhotoCount') ;
	let $counter_input = $('#txtLastKey') ;
	let $new_key =   parseInt($counter_input.val()) + 1;
	let $new_selector = '#divPhoto-'+$new_key.toString() ;
	let $new_counter = parseInt(forms_digit_en($counter_label.html())) + 1;

	$counter_input.val($new_key);
	$counter_label.html(forms_digit_fa($new_counter.toString()));

	$new_div = $new_div.replace('NEW' , $new_key) ;
	$new_div = $new_div.replace('NEW' , $new_key) ;
	$new_div = $new_div.replace('NEW' , $new_key) ;
	$new_div = $new_div.replace('NEW' , $new_key) ;

	$('#divPhotos').append($new_div);
	$($new_selector + ' input.-src').val($src) ;
	$($new_selector + ' input.-label').focus() ;
	$($new_selector + ' img').attr('src', $src) ;
	$($new_selector).slideDown() ;

}

function postPhotoRemoved($selector)
{

	$selector.parent().parent().slideUp().html('') ;

	let $counter_label = $('#spnPhotoCount') ;
	let $new_counter = parseInt(forms_digit_en($counter_label.html())) -1;
	$counter_label.html(forms_digit_fa($new_counter.toString()));

}

function downstreamPhotoSelected($input_selector)
{
	$($input_selector).val($($input_selector).val().replace(url(),''));
}

function downstreamPhotoPreview($input_selector)
{
	$url = $($input_selector).val() ;
	if($url)
		window.open(url($url)) ;
}

function currencyRateUpdateEditor()
{
	$type = $('#cmbEffectiveDate').val() ;

	switch($type) {
		case 'now' :
			$('.-custom_time').parent().parent().hide() ;
			$('input[name=price_to_buy]').focus() ;
			break;

		case 'custom' : //legal
			$('.-custom_time').parent().parent().show() ;
			$('.-individual').parent().parent().hide() ;
			$('input[name=date]').focus() ;
	}

}

function customerEditor()
{
	$type = $('#cmbCustomerType').val() ;

	switch($type) {
		case '1' : //individual
			$('.-individual').parent().parent().show() ;
			$('.-legal').parent().parent().hide() ;
			$('input[name=name_first]').focus() ;
			break;

		case '2' : //legal
			$('.-legal').parent().parent().show() ;
			$('.-individual').parent().parent().hide() ;
			$('input[name=name_firm]').focus() ;
	}

}

function orderEditor()
{
	var $rate = parseFloat($('input[name=rate]').val()) ;
	var $amount = parseFloat(  forms_digit_en($('input[name=initial_charge]').val().replaceAll(',','') ) )
	if(!$amount)
		$amount = 0 ;
	$invoice = forms_digit_fa(addCommas(Math.round($rate * $amount))) ;

	$('input[name=original_invoice]').val( $invoice ) ;
	$('input[name=amount_invoiced]').val( $invoice ) ;
	$('input[name=invoice]').val( $invoice ) ;
}

function paymentEditor()
{
	var $method = $('#cmbMethodSelector').val() ;

	$('.-detail').parent().parent().hide() ;
	$('.-'+$method).parent().parent().show() ;
}

function paymentProcessEditor()
{
	var $selector_value = $('#cmbStatus').val();
	$('.saveButton').hide() ;

	switch($selector_value) {
		case 'confirmed' :
			$('#btnConfirm').show() ;
			$('#txtConfirmed').val( $('#txtDeclared').val() ).parent().parent().hide();
			break;

		case 'rejected' :
			$('#btnReject').show() ;
			$('#txtConfirmed').val( '0' ).parent().parent().hide();
			break;

		case 'custom' :
			$('#btnSave').show() ;
			$('#txtConfirmed').val( $('#txtConfirmed').attr('aria-valuenow') ).parent().parent().show();
			forms_numberFormat('#txtConfirmed');
			$('#txtConfirmed').focus();
			break;

		default :
			$('#btnSave').show() ;
			$('#txtConfirmed').val('').parent().parent().hide();
			break;

	}
}

function sidebarToggle($speed)
{
	if(!$speed) $speed = 0 ;
	$current_sitation = localStorage.getItem('sidebar') ;
	if(!$current_sitation) $current_sitation = "shown" ;

	if($current_sitation=="shown") {
		//hide command:
		$(".sidebar").hide();
		$("#sidebarHandle").removeClass('fa-chevron-right').addClass('fa-chevron-left');
		localStorage.setItem('sidebar' , 'hidden');
		$("#page-wrapper").animate({
			"margin-right":0,
		},$speed);
	}
	else {
		//show command:
		$("#page-wrapper").animate({
			"margin-right":200,
		},$speed , function() {
			$(".sidebar").show();
			$("#sidebarHandle").removeClass('fa-chevron-left').addClass('fa-chevron-right');
		});
		localStorage.setItem('sidebar' , 'shown');
	}

	return localStorage.getItem('sidebar') ;
}

function sidebarInitiate()
{
	$current_sitation = localStorage.getItem('sidebar') ;
	if($current_sitation=='hidden') {
		localStorage.setItem('sidebar' , 'shown');
		return sidebarToggle(0);
	}
}