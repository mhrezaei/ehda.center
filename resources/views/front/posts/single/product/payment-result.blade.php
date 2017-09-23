@if(isset($paymentSucceeded))
    <div class="row" id="payment-result">
        @if($paymentSucceeded)
            @php $alertType = 'success' @endphp
        @else
            @php $alertType = 'danger' @endphp
        @endif
        <div class="alert alert-{{ $alertType }}">
            {!! $paymentMsg !!}
            <br/>
            @if($trackingNumber)
                {{ trans('validation.attributes.tracking_number') }}:
                {{ ad($trackingNumber) }}
            @endif
        </div>
    </div>
@endif