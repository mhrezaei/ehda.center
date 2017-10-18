@include('templates.modal.start' , [
	'partial' => true ,
//	'form_url' => url('manage/posts/save/soft_delete'),
	'modal_title' => trans('ehda.cards.stats'),
])

<div class='modal-body'>


	<div class="panel panel-default"><div class="panel-heading">

		<div class="row">
			<div class="col-md-1 p5 m5 text-center">
				{{ trans("forms.general.from") }}
			</div>

			<div class="col-md-3">
				@include('forms.datepicker' , [
					'name' => 'date' ,
					'id' => 'txtDate' ,
					'value' => \Carbon\Carbon::now() ,
					'in_form' => 0 ,
					'class' => "text-center",
				])
			</div>

			<div class="col-md-1 p5 m5 text-center">
				{{ trans("forms.general.till") }}
			</div>

			<div class="col-md-3 text-center">
				@include("forms.input-self" , [
					'name' => "",
					'extra' => "disabled" ,
					'value' => trans("ehda.cards.stats_duration") ,
					'class' => "text-center" ,
				]     )
			</div>

			<div class="col-md-3 text-center">
				@include("forms.button" , [
					'label' => trans("forms.button.Inquiry") ,
					'shape' => "primary w80" ,
					'link' => "inlineInquery()" ,
				])
			</div>

		</div></div>

	</div>


	<div id="divStatsResult" class="mv10 w100" ></div>

</div>


@include('templates.modal.end')

<script>
	function inlineInquery()
	{
		let $date = $('#txtDate_extra') ;
		let $result_area = $('#divStatsResult') ;
		let date = $date.val().replaceAll(/\// , '-') ;


	    $result_area.addClass('loading') ;


	    $.ajax({
		    url  : "{{ route("card-stats") }}/" + date ,
		    cache: false,
	    })
		    .done(function (html) {
			    $($result_area).html(html);
			    $($result_area).removeClass('loading');
		    });
    }
</script>