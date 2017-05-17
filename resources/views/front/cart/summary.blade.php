<table class="table bordered cart-total">
    <tbody>
    <tr>
        <td>{{ trans('cart.your_total_payable') }}</td>
        <td> @pd(number_format($cart->sum)) {{ trans('front.toman') }}</td>
    </tr>
    <tr>
        <td> {{ trans('validation.attributes.discount') }} </td>
        <td> @pd(number_format($cart->discount)) {{ trans('front.toman') }}</td>
    </tr>
    <tr class="total">
        <td> {{ trans('cart.payable') }} </td>
        <td> @pd(number_format($cart->sum - $cart->discount)) {{ trans('front.toman') }}</td>
    </tr>
    </tbody>
</table>