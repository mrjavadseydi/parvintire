@extends("admin.{$adminTheme}.master")
@section('title', 'مشاهده سفارش')

@section('content')

    <div class="divider">
        <span>سفارش {{ $order->id }}# <small>ثبت شده در تاریخ {{ jDateTime('j F Y', strtotime($order->created_at)) }}</small></span>
    </div>

    <div class="p25">

        <div class="single-specification tar">
            <h2>وضعیت ارسال</h2>

            <div class="row tac mb50">

                <div class="col-lg-3 pr0">
                    <img width="100px" src="{{ asset('/images/order/submited.png') }}" alt="سفارش ثبت شد">
                    <label class="db">ثبت شد</label>
                    <small class="db ltr gray">{{ jDateTime('Y/m/d H:i:s', strtotime($order->updated_at)) }}</small>
                </div>

                <div class="col-lg-3 pl0">
                    <img class="{{ $orderMetas['status'] >= 1 ? '' : 'image-gray' }}" width="100px" src="{{ asset('/images/order/preparing.png') }}" alt="در حال آماده سازی سفارش">
                    <label class="db">در حال آماده سازی</label>
                    <small class="db ltr gray">{{ $orderMetas['status'] >= 1 ? $statusDateTime[1] : '' }}</small>
                </div>

                <div class="col-lg-3 pr0">
                    <img class="{{ $orderMetas['status'] >= 2 ? '' : 'image-gray' }}" width="100px" src="{{ asset('/images/order/sent.png') }}" alt="سفارش ارسال شد">
                    <label class="db">ارسال شد</label>
                    <small class="db ltr gray">{{ $orderMetas['status'] >= 2 ? $statusDateTime[2] : '' }}</small>
                </div>

                <div class="col-lg-3 pl0">
                    <img class="{{ $orderMetas['status'] == 3 ? '' : 'image-gray' }}" width="100px" src="{{ asset('/images/order/delivered.png') }}" alt="سفارش تحویل داده شد">
                    <label class="db">تحویل داده شد</label>
                    <small class="db ltr gray">{{ $orderMetas['status'] == 3 ? $statusDateTime[3] : '' }}</small>
                </div>

            </div>

        </div>

        <div class="single-specification tar">
            <h2>اطلاعات سفارش</h2>

            <div class="row">

                <div class="col-lg-6 pr0">
                    <div class="single-specification-item">
                        <div class="key">
                            <span>مبلغ سفارش</span>
                        </div>
                        <div class="values">
                            <span>{{ number_format($transaction->price - $orderMetas['postage']) }} تومان</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 pr0">
                    <div class="single-specification-item">
                        <div class="key">
                            <span>هزینه ارسال</span>
                        </div>
                        <div class="values">
                            <span>{{ ($orderMetas['postage'] == 0 ? "رایگان" : number_format($orderMetas['postage']) . ' تومان') }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 pr0">
                    <div class="single-specification-item">
                        <div class="key">
                            <span>مبلغ قابل پرداخت</span>
                        </div>
                        <div class="values">
                            <span class="green">{{ number_format($transaction->price) }} تومان</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 pr0">
                    <div class="single-specification-item">
                        <div class="key">
                            <span>شماره پیگیری</span>
                        </div>
                        <div class="values">
                            <span class="orange">{{ $transaction->reference_id }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 pr0">
                    <div class="single-specification-item">
                        <div class="key">
                            <span>درگاه</span>
                        </div>
                        <div class="values">
                            <span>{{ config("gateway.{$transaction->gateway}.name") }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 pr0">
                    <div class="single-specification-item">
                        <div class="key">
                            <span>نام و نام خانوادگی</span>
                        </div>
                        <div class="values">
                            <span>{{ $orderMetas['name'] }} {{ $orderMetas['family'] }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 pr0">
                    <div class="single-specification-item">
                        <div class="key">
                            <span>موبایل</span>
                        </div>
                        <div class="values">
                            <span>{{ $orderMetas['mobile'] }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 pr0">
                    <div class="single-specification-item">
                        <div class="key">
                            <span>تلفن ثابت</span>
                        </div>
                        <div class="values">
                            <span>{{ $orderMetas['phone'] }}</span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="single-specification-item">
                <div class="key">
                    <span>توضیحات</span>
                </div>
                <div class="values">
                    <span>{{ $orderMetas['description'] }}</span>
                </div>
            </div>

        </div>

        <div class="single-specification tar mt50">
            <h2>محصولات</h2>

            @php $i = 1; @endphp
            <div class="responsive-table">
                <table class="tac">
                    <thead>
                    <tr>
                        <th></th>
                        <th>نام محصول</th>
                        <th>تعداد</th>
                        <th>قیمت واحد (تومان)</th>
                        <th>قیمت کل (تومان)</th>
                        <th>تخفیف</th>
                        <th>قیمت نهایی (تومان)</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($carts as $cart)
                        @php
                            $post = \App\Post::where('id', $cart->post_id)->first();
                            $unit = $post->unit();
                        @endphp
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>
                                <img width="50px" src="{{ $post->image() }}" alt="{{ $post->title }}">
                                <span class="db">{{ $post->title }}</span>
                            </td>
                            <td>{{ $cart->count }} {{ $unit->title }}</td>
                            <td>{{ number_format($cart->price) }}</td>
                            <td>{{ $cart->price * $cart->count }}</td>
                            <td>{{ $cart->discount }}</td>
                            <td>{{ $cart->price * $cart->count }}</td>
                            <td>
                                <input type="hidden" name="addToCartUrl" value="{{ url("/cart/add/{$post->id}") }}">
                                <select class="dn" name="count">
                                    <option value="{{ $unit->coefficient }}"></option>
                                </select>
                                <a href="{{ $post->href() }}" class="fs15 btn-lg db btn-success mb2">خرید مجدد</a>
                                <a href="{{ url('/comment/' . $post->slug) }}" class="fs15 btn-lg db btn-warning">ثبت نظر</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>

        <div class="single-specification tar mt50">
            <h2>مشخصات تحویل گیرنده</h2>

            <div class="row">

                <div class="col-lg-6 pr0">
                    <div class="single-specification-item">
                        <div class="key">
                            <span>نام و نام خانوادگی</span>
                        </div>
                        <div class="values">
                            <span>{{ $orderMetas['name_family'] }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 pl0">
                    <div class="single-specification-item">
                        <div class="key">
                            <span>موبایل</span>
                        </div>
                        <div class="values">
                            <span>{{ $orderMetas['mobile_number'] }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 pr0">
                    <div class="single-specification-item">
                        <div class="key">
                            <span>استان</span>
                        </div>
                        <div class="values">
                            <span>{{ $province }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 pl0">
                    <div class="single-specification-item">
                        <div class="key">
                            <span>شهر</span>
                        </div>
                        <div class="values">
                            <span>{{ $city }}</span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="single-specification-item">
                <div class="key">
                    <span>آدرس</span>
                </div>
                <div class="values">
                    <span>{{ $orderMetas['address'] }}</span>
                </div>
            </div>

            <div class="row">

                <div class="col-lg-12 p0">
                    <div class="single-specification-item">
                        <div class="key">
                            <span>کد پستی</span>
                        </div>
                        <div class="values">
                            <span>{{ $orderMetas['postal_code'] }}</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

@endsection
