@extends(includeTemplate('profile.master'))
@section('title', 'سفارش ' . $order->id)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('profile/orders') }}">سفارشات</a></li>
    <li class="breadcrumb-item active" aria-current="page">سفارش {{ $order->id }}</li>
@endsection
@section('main')
    <div class="iransansFa">
        <div class="row table-bordered rounded mx-4 mb-3 mt-0 p-2">
            <div class="col-md-3">
                <h5 class="text-muted">تحویل گیرنده</h5>
                <span>{{ $address->name_family }}</span>
            </div>
            <div class="col-md-3">
                <h5 class=" text-muted">شماره تماس</h5>
                <span>{{ $address->mobile }}</span>
            </div>
            <div class="col-md-3">
                <h5 class="text-muted">کد پستی</h5>
                <span>{{ $address->postal_code }}</span>
            </div>
            <div class="col-md-3">
                <h5 class="text-muted">مبلغ سفارش</h5>
                <span class="text-success">{{ number_format(convertPrice($order->payed_price)) }} تومان</span>
            </div>
            <div class="col-md-12 border-top mt-2 pt-2">
                <h5 class="text-muted">آدرس</h5>
                <span>{{ $address->address() }}</span>
            </div>
        </div>
        @foreach($shippings as $orderShippingId => $shipping)

            <div class="mt-5">

                @include(includeTemplate('divider.2'), ['title' => $shipping['shipping']->title . " (<small>{$shipping['postage']}</small>)"])

                <div class="row col-md-10 mx-auto order-send-status my-3">

                    @foreach(config('store.sendStatus') as $statusId => $status)
                        <?php
                        $st = null;
                        if (isset($statuses[$orderShippingId][$statusId]))
                            $st = $statuses[$orderShippingId][$statusId];
                        ?>
                        <div class="item {{ $st == null ? '' : 'active' }} col-md-3 text-center">
                            <figure class="text-center m-0">
                                <img width="60" src="{{ image("order/{$statusId}.png") }}" alt="submited">
                                <figcaption class="text-center">{{ $status['title'] }}</figcaption>
                            </figure>
                            <small class="time text-muted">{{ $st == null ? '' : jDateTime('Y/m/d H:i:s', strtotime($st)) }}</small>
                        </div>
                    @endforeach

                </div>

                <div class="row col-md-11 mx-auto mt-3">

                    <div class="table-responsive">
                        <table class="table orders-table">
                            <thead>
                            <tr>
                                <th class="text-right">نام محصول</th>
                                <th>تعداد</th>
                                <th>قیمت واحد</th>
                                <th>قیمت کل</th>
                                <th>تخفیف</th>
                                <th>قیمت نهایی</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($shipping['carts'] as $cart)
                                <?php
                                $c = $cart['cart'];
                                $post = $cart['post'];
                                $product = $cart['product'];
                                ?>
                                <tr>
                                    <td class="text-muted">
                                        <a href="{{ $post->href() }}&productId={{ $product->product_id }}">
                                            <figure class="m-0">
                                                <img src="{{ $post->thumbnail(50, 50) }}" alt="{{ $post->title }}">
                                                <figcaption class="d-inline-block mr-1">{{ $product->title }}</figcaption>
                                            </figure>
                                        </a>
                                    </td>
                                    <td class="text-muted">{{ $c->count }}</td>
                                    <td class="text-muted">
                                        <span>{{ number_format(convertPrice($c->price + $c->discount)) }}</span>
                                        <br>
                                        <span>تومان</span>
                                    </td>
                                    <td class="text-muted">
                                        <span>{{ number_format(convertPrice(($c->price + $c->discount) * $c->count)) }}</span>
                                        <br>
                                        <span>تومان</span>
                                    </td>
                                    <td class="text-muted">
                                        <span class="text-danger">{{ number_format(convertPrice($c->discount * $c->count)) }}</span>
                                        <br>
                                        <span class="text-danger">تومان</span>
                                    </td>
                                    <td class="text-muted">
                                        <span class="text-success">{{ number_format(convertPrice($c->total_price)) }}</span>
                                        <br>
                                        <span class="text-success">تومان</span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>

        @endforeach
    </div>
@endsection
