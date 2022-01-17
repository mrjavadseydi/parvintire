<div class="border rounded px-3 py-1 mt-2">
    <div class="d-flex justify-content-between align-items-center py-2">
        <h6><i class="fa fa-plus text-success align-middle ml-2"></i>تعداد</h6>
        <h6>{{ $cart['productsSumCount'] }} کالا</h6>
    </div>
    <div class="d-flex justify-content-between align-items-center py-2">
        <h6><i class="fa fa-plus text-success align-middle ml-2"></i>مبلغ کل سفارش</h6>
        <h6>{{ number_format($cart['productsPrice']) }} تومان</h6>
    </div>
    <div class="d-flex justify-content-between align-items-center py-2">
        <h6><i class="fa fa-minus text-danger align-middle ml-2"></i>تخفیف</h6>
        <h6 class="text-danger">{{ number_format($cart['cartDiscount']) }} تومان</h6>
    </div>
    <div class="d-flex justify-content-between align-items-center py-2">
        <h6><i class="fa fa-plus text-success align-middle ml-2"></i> مالیات مجموع
        </h6>
        <h6>{{ number_format($cart['tax']) }} تومان</h6>
    </div>
    @foreach($cart['shippings'] as $shippingId => $values)
        @if(isset($values['carts']))
            <div class="d-flex justify-content-between align-items-center py-2">
                <h6><i class="fa fa-plus text-success align-middle ml-2"></i>هزینه {{ $values['shipping']->title }}
                    {{-- @if($values['toFreePostage'] > 0)
                        <br>
                        <small class="text-muted">{{ number_format($values['toFreePostage']) }} تومان تا رایگان</small>
                    @endif --}}
                </h6>
                <h6>{{ $values['postage'] }}</h6>
            </div>
        @endif
    @endforeach
    <div class="d-flex justify-content-between align-items-center border-top py-2">
        <h5>مبلغ قابل پرداخت</h5>
        <h5 class="text-success">{{ number_format($cart['payablePrice']) }} تومان</h5>
    </div>
</div>
