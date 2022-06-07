@extends(includeTemplate('master'))
@section('title', 'سبدخرید | آدرس')
@section('content')

    <div class="container py-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item"><a href="{{ url('cart') }}">سبد خرید</a></li>
                <li class="breadcrumb-item active" aria-current="page">ثبت مشخصات</li>
            </ol>
        </nav>
        <div class="row cart bg-white border rounded">
            <script src="{{ asset('assets/admin/default/store.js') }}"></script>
            <form callback="cartAddressCallback" id="cart-address-form" action="{{ route('order.more') }}" method="post"
                  class="carts p-3">
                <?php
                $address = $cart['address'];
                $cityId = $townId = $regionId = null;
                $cities = $towns = $regions = [];
                if ($address != null) {
                    $cityId = $address->city_id;
                    $townId = $address->town_id;
                    $regionId = $address->region_id;
                    if ($cityId != null) {
                        $cities = \LaraBase\World\models\City::where(['province_id' => $address->province_id, 'active_postage' => '1'])->get();
                    }
                    if ($townId != null) {
                        $towns = \LaraBase\World\models\Town::where(['city_id' => $cityId, 'active_postage' => '1'])->get();
                    }
                    if ($regionId != null) {
                        $regions = \LaraBase\World\models\Region::where(['town_id' => $townId, 'active_postage' => '1'])->get();
                    }
                }
                ?>
                @csrf
                <div class="shipping mb-2 row">
                    <div class="col-12">
                        <h5 class="d-inline-block">ثبت مشخصات</h5>
                    </div>
                    {{--                    <div class="col-3 text-left">--}}
                    {{--                        <small class="font-weight-normal d-inline-block btn-sm btn-danger pointer">انتخاب آدرس</small>--}}
                    {{--                    </div>--}}
                </div>

                <div class="row">
                    <div class="col-md-6 mt-3">
                        <div class="input-group">
                            <label>کدملی</label>
                            @php $ncode = auth()->user()->getMeta('nationalCode')->value ?? '' @endphp
                            <input type="text" name="{{$ncode != '' ? '' : 'nationalCode'}}" value="{{ $ncode }}"
                                   {{$ncode != '' ? 'disabled' : ''}} class="form-control ltr text-left rounded w-100">
                            @if($ncode != '')
                                <input type="hidden" name="nationalCode" value="{{ $ncode }}"
                                       class="form-control ltr text-left rounded w-100">
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6 mt-3">
                        <div class="input-group">
                            <label>نام و نام خانوادگی </label>
                            <input type="text" name="nameFamily" value="{{ auth()->user()->name ?? '' }}"
                                   class="form-control rounded w-100">
                        </div>
                    </div>


                </div>
                <div class="row mt-3">
                    <div class="col-6">
                        <a href="{{ url('cart') }}" class="btn btn-outline-warning px-4 py-2">بازگشت</a>
                    </div>
                    <div class="col-6 text-left">
                        <button class="btn btn-success px-4 py-2 btn-address">ثبت مشخصات و پرداخت</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        $(document).on('click', '.btn-address', function () {
            $(this).text('لطفا صبر کنید...');
            $('#cart-address-form').submit();
        });

        function cartAddressCallback(status, response) {
            $('.btn-address').text('ثبت مشخصات و پرداخت');
            if (status == 'success') {
                window.location = '{{ route('cart.payment') }}';
            }
        }

    </script>
@endsection
