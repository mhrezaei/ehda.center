<tr>
	<td style="vertical-align: middle"><span class="fa fa-plus"></span></td>
	<td colspan="2">
		@include("forms.hidden" , [
			'name' => "user_id",
			'value' => $model->id,
		])
		@include("forms.input-self" , [
			'id' => "txtPurchaseCode",
			'name' => "code",
			'placeholder' => trans('cart.add_new_receipt'),
			'class' => "text-center ltr",
		])
	</td>
	<td>
		@include("forms.button" , [
			'label' => trans('forms.button.send_and_save'),
			'type' => "submit",
		])
	</td>
</tr>
<script>$("#txtPurchaseCode").inputmask("99999-99999-99999-99999");</script>