@extends(includeTemplate('master'))
@section('title', 'سبدخرید |‌ پرداخت')
@section('content')
    <div class="cart-view-2">
        @include(includeTemplate('order.payment-view'))
    </div>
@endsection
