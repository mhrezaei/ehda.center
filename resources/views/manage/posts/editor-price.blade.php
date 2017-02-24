@if($model->has('price'))
	<div class="panel panel-info">
		<div class="panel-heading">
			<i class="fa fa-money mh5"></i>
			{{ trans('validation.attributes.price') }}
		</div>
		
		<div class="panel-body">

			{{--
			|--------------------------------------------------------------------------
			| Main Price
			|--------------------------------------------------------------------------
			|
			--}}


			<div class="row">
				<div class="col-md-6">

					@include("forms.input-self" , [
						'name' => "price",
						'id' => "txtPrice",
						'top_label' => trans('validation.attributes.original_price'),
						'class' => "form-numberFormat ltr text-center",
						'addon' => setting('currency')->in($model->locale)->gain(),
						'group_class' => "form-group-sm",
						'value' => $model->price,
						'on_change' => "postCalcSale('price')",
					])

				</div>
				<div class="col-md-6 text-center">

					{{--<br />--}}
					{{--@include("forms.button" , [--}}
						{{--'label' => trans('posts.form.sale_settings'),--}}
						{{--'shape' => "default btn-sm",--}}
						{{--'id' => "btnSale",--}}
						{{--'link' => "postTogglePrice()",--}}
						{{--'class' => !$model->sale_price? '' : 'noDisplay',--}}
					{{--])--}}

				</div>
			</div>

			{{--
			|--------------------------------------------------------------------------
			| Sale Price
			|--------------------------------------------------------------------------
			|
			--}}


			<div class="row">
				<div class="col-md-6">

					@include("forms.input-self" , [
						'id' => "txtSalePrice",
						'name' => "sale_price",
						'top_label' => trans('validation.attributes.sale_price'),
						'class' => "form-numberFormat ltr text-center salePrice",
						'addon' => setting('currency')->in($model->locale)->gain(),
						'group_class' => "form-group-sm",
						'value' => $model->sale_price,
						'on_change' => "postCalcSale('sale')",
					])


				</div>
				<div class="col-md-6" style="opacity: 0.5">


					@include("forms.input-self" , [
						'id' => "txtSalePercent",
						'name' => "sale_discount",
						'top_label' => trans('validation.attributes.discount_percent'),
						'class' => "form-number ltr text-center salePrice",
						'addon' => "%",
						'group_class' => "form-group-sm",
						'value' => $model->discount_percent,
						'on_change' => "postCalcSale('percent')",
					])


				</div>
			</div>

			{{--
			|--------------------------------------------------------------------------
			| Package and Availability 
			|--------------------------------------------------------------------------
			|
			--}}


			<div class="row">
				<div class="col-md-6">

					{{-- Package id --}}
					@include("forms.select_self" , [
						'name' => "package_id",
						'top_label' => trans('validation.attributes.package_id'),
						'top_label_class' => "mv5",
						'options' => $model->packageCombo(),
						'value' => $model->package_id,
					])

				</div>
				<div class="col-md-6">

					{{-- Availabilty --}}
					@include("forms.select_self" , [
						'name' => "is_available",
						'top_label' => trans('validation.attributes.inventory'),
						'top_label_class' => "mv5",
						'options' => [
							['1' , trans('posts.form.is_available')],
							['0' , trans('posts.form.is_not_available')],
						],
						'value' => $model->is_available,
						'caption_field' => "1",
						'value_field' => "0",
						'class' => "f10",
					])

				</div>
			</div>


		</div>
	</div>

@endif