<table class="table bordered">
    <thead>
    <tr>
        <th>{{ trans('validation.attributes.product') }}</th>
        <th>{{ trans('cart.unit_price') }}</th>
        <th>{{ trans('cart.number') }}</th>
        <th>{{ trans('cart.total_price') }}</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach($cart->items as $item)
        <tr>
            <td>
                <a href="{{ $item->product->direct_url }}" class="product-name">
                    <img src="{{ $item->product->viewable_featured_image }}" width="80">
                    <h5> {{ $item->product->title }} </h5>
                </a>
            </td>
            <td class="price"> @pd(number_format($item->product->price)) {{ trans('front.toman') }} </td>
            <td class="tac"> @pd($item->count) </td>
            <td class="price"> @pd(number_format($item->product->price * $item->count)) {{ trans('front.toman') }} </td>
            <td class="remove-row">
                <a href="#" class="icon-close"></a>
            </td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td colspan="100%" class="tal">
            <a href="#" class="button"> {{ trans('cart.empty_cart') }} </a>
            <a href="#" class="button green"> {{ trans('cart.settlement') }} </a>
        </td>
    </tr>
    </tfoot>
</table>