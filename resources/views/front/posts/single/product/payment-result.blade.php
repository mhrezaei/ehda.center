@if(isset($paymentSucceeded) and isset($paymentSucceeded))
    <div class="row" id="payment-result">
        @if($paymentSucceeded)
            @php $alertType = 'success' @endphp
        @else
            @php $alertType = 'danger' @endphp
        @endif
        <div class="alert alert-{{ $alertType }}">
            {!! $paymentMsg !!}
        </div>
    </div>
@endif