<?php
$address = $cart['address'];
$cityId = $townId = $regionId = null;
$cities = $towns = $regions = [];
$textAddress = null;
if ($address != null) {
    $cityId = $address->city_id;
    $townId = $address->town_id;
    $regionId = $address->region_id;
    $province = \LaraBase\World\models\Province::find($address->province_id);
    $textAddress .= 'استان '. $province->name . '، ';
    if ($cityId != null) {
        $city = \LaraBase\World\models\City::find($address->city_id);
        $textAddress .= 'شهرستان ' . $city->name . '، ';
    }
    if ($townId != null) {
        $town = \LaraBase\World\models\Town::find($address->town_id);
        $textAddress .= 'شهر ' . $city->name . '، ';
    }
    if ($regionId != null) {
        $region = \LaraBase\World\models\Region::find($address->region_id);
        $textAddress .= 'منطقه ' . $city->name . '، ';
    }
    $textAddress .= $address->address;
}
?>
<div class="container-fluid px-6 py-3 iransansFa cart">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">صفحه اصلی</a></li>
            <li class="breadcrumb-item"><a href="{{ url('cart') }}">سبد خرید</a></li>
            <li class="breadcrumb-item"><a href="{{ url('cart/address') }}">آدرس تحویل سفارش</a></li>
            <li class="breadcrumb-item active" aria-current="page">تایید اطلاعات و پرداخت</li>
        </ol>
    </nav>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <h5 class="col-md-4 font-weight-normal iransansLightFa">نام و نام خانوادگی:‌ {{ $address->name_family }}</h5>
                <h5 class="col-md-4 font-weight-normal iransansLightFa">موبایل: {{ $address->mobile }}</h5>
                <h5 class="col-md-4 font-weight-normal iransansLightFa">کدپستی: {{ $address->postal_code }}</h5>
            </div>
            <div class="row pt-3">
                <h5 class="col font-weight-normal iransansLightFa">{{ $textAddress }}</h5>
            </div>
            <div class="text-left pt-3">
                <a href="{{ url('cart/address') }}" class="btn btn-sm btn-outline-warning">ویرایش آدرس</a>
            </div>
        </div>
    </div>
    <br>
    <br>
    @include(includeTemplate('divider.2'), ['title' => 'محصولات'])
    @if(isset($cart['shippings']))
        @foreach($cart['shippings'] ?? [] as $shipping)
            @if(isset($shipping['carts']))
                @foreach($shipping['carts'] as $item)
                    <div class="card mt-2">
                        <div class="card-body d-flex justify-content-between align-items-center p-2">
                            <figure class="ml-2">
                                <img src="{{ $item['post']->thumbnail(70, 70) }}" alt="">
                            </figure>
                            <h5 class="flex-fill">{{ $item['product']->title }}</h5>
                            <h5 class="ltr">{{ $item['cart']->count }} x {{ number_format($item['product']->price()) }} = <span class="text-success">{{ number_format($item['cart']->count*$item['product']->price()) }}</span></h5>
                        </div>
                    </div>
                @endforeach
            @endif
        @endforeach
    @endif
    @include(includeTemplate('order.payment-info'))
    <br>
    <br>
    @include(includeTemplate('divider.2'), ['title' => 'نوع پرداخت'])
    <form id="cart-payment-form" action="{{ route('order.payment') }}" method="post" class="carts">
        @csrf
        <div class="card mt-2">
            <label for="zarinpal" class="gateway card-body d-flex justify-content-between align-items-center">
                <input checked class="ml-3" id="zarinpal" type="radio" name="gateway" value="ZarinPal">
                <div class="d-flex flex-column justify-content-around flex-fill">
                    <h5 class="iransansLightFa">پرداخت آنلاین با تمام کارت های عضو شتاب</h5>
                    <h5 class="iransansLightFa pt-2">درگاه ملت</h5>
                </div>
                <figure>
                    <img width="60" height="60" src="{{ image('gateway.jpg', 'template') }}" alt="gateway">
                </figure>
            </label>
        </div>
        <?php $wallet = getWalletCredit();?>
        @if($wallet >= $cart['payablePriceRial'])
        <div class="card mt-2">
            <label for="wallet" class="gateway card-body d-flex justify-content-between align-items-center">
                <input class="ml-3" id="wallet" type="radio" name="gateway" value="wallet">
                <div class="d-flex flex-column justify-content-around flex-fill">
                    <h5 class="iransansLightFa">پرداخت از طریق کیف پول</h5>
                    <h5 class="iransansLightFa pt-2">مانده حساب شما: <b class="text-success">{{ number_format(convertPrice($wallet)) }} تومان</b></h5>
                </div>
                <figure>
                    <img width="60" height="60" src="{{ image('wallet.png', 'template') }}" alt="wallet">
                </figure>
            </label>
        </div>
        @endif
        <div class="row mt-3">
            <div class="col-6">
                <a href="{{ url('cart/address') }}" class="btn btn-outline-success px-4 py-2">بازگشت</a>
            </div>
            <div class="col-6 text-left">
                <span class="btn btn-success px-4 py-2 btn-payment">پرداخت و ثبت نهایی سفارش</span>
            </div>
        </div>
        <script src="{{ asset('assets/admin/default/store.js') }}"></script>
        <script>
            $(document).on('click', '.btn-payment', function () {
                $(this).text('در حال انتقال به درگاه پرداخت');
                $('#cart-payment-form').submit();
            });
        </script>
    </form>
</div>
