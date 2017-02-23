/*
 |--------------------------------------------------------------------------
 | On Load ...
 |--------------------------------------------------------------------------
 |
 */

function postsInit()
{
}

/*
 |--------------------------------------------------------------------------
 | Helpers
 |--------------------------------------------------------------------------
 |
 */
function postToggleTitle2()
{
	$('#lblTitle2,#txtTitle2').toggle();
	$("#txtTitle2").focus();
}

function postToggleSchedule( $mood )
{
	if(!$mood) {
		forms_log("mood="+$mood);
		if($("#divSchedule").is(':visible'))
			$mood = 'hide' ;
		else
			$mood = 'show' ;
	}
	switch ($mood) {
		case 'show' :
			$("#divSchedule").slideDown('fast');
			$("#lnkSchedule").hide();
			$("#txtPublishDate").focus() ;
			break;

		case 'hide' :
			$("#divSchedule").slideUp('fast');
			$("#lnkSchedule").show();
			$("#txtPublishDate").val('');
			$("#cmbPublishDate").val('08:00');
			$('.selectpicker').selectpicker('refresh');
			break;

	}

	return $mood ;

}

function postsAction($command)
{
	switch($command) {
		case 'adjust_publish_time' :
			postToggleSchedule('show') ;
			break;
	}
	forms_log('action: '+$command);
}