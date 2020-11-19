@extends("admin.{$adminTheme}.master")
@section('title', 'ویرایش سفارش')

@section('content')

    <div class="col-lg-12">

        @if($address != null)
        <div method="post" class="box-solid box-primary">

            <div class="box-header">
                <h3 class="box-title">اطلاعات ارسال</h3>
                <div class="box-tools">
                    <i class="box-tools-icon icon-minus"></i>
                </div>
            </div>

            <div class="box-body">
                <label class="db">تحویل گیرنده : {{ $address->name_family }}</label>
                <label class="db">شماره تماس : {{ $address->mobile }}</label>
                <label class="db">کد پستی : {{ $address->postal_code }}</label>
                <label class="db">مبلغ سفارش : {{ $transaction == null ? '-' : number_format($transaction->price) }} ریال</label>
                <label class="db">آدرس : {{ $address->address() }}</label>
            </div>

        </div>
        @endif
        @if(!empty($shippings))
            @foreach($shippings as $orderShippingId => $shipping)
                @if(isset($shipping['carts']))
                    <div method="post" class="box-solid box-info">

                        <div class="box-header">
                            <h3 class="box-title">{{ $shipping['shipping']->title }} ({{ $shipping['postage'] }})</h3>
                            <div class="box-tools">
                                <i class="box-tools-icon icon-minus"></i>
                            </div>
                        </div>

                        <div class="box-body">

                            <div style="margin: auto" class="row col-md-10 mx-auto order-send-status my-3">
                                @foreach(config('store.sendStatus') as $statusId => $status)
                                    <?php
                                    $st = null;
                                    if (isset($statuses[$orderShippingId][$statusId]))
                                        $st = $statuses[$orderShippingId][$statusId];
                                    ?>
                                    <form id="s-{{ $orderShippingId }}-s-{{ $statusId }}" onSuccess="changeStatus" action="{{ url('api/v1/orders/setStatus') }}" method="post" class="ajaxForm item {{ $st == null ? '' : 'active' }} col-md-3 tac">
                                        @csrf
                                        <input type="hidden" name="orderId" value="{{ $order->id }}">
                                        <input type="hidden" name="shippingId" value="{{ $orderShippingId }}">
                                        <input type="hidden" name="status" value="{{ $statusId }}">
                                        <figure class="submit-status-form text-center m-0 pointer">
                                            <img width="60" src="{{ image("order/{$statusId}.png") }}" alt="submited">
                                            <figcaption class="text-center">{{ $status['title'] }}</figcaption>
                                        </figure>
                                        <small class="time text-muted">{{ $st == null ? '' : jDateTime('H:i:s Y/m/d', strtotime($st)) }}</small>
                                        @if($statusId == 3)
                                            <input placeholder="کدرهگیری" type="text" class="ltr text-left w100" name="trackingCode" value="{{ $shipping['orderShipping']->tracking_code }}">
                                        @endif
                                    </form>
                                @endforeach
                            </div>

                            <div class="table-responsive mt20">
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
                @else
                    <h3>سبد خرید این سفارش خالی می باشد</h3>
                @endif
            @endforeach
        @else
            <h3>سبد خرید این سفارش خالی می باشد</h3>
        @endif

    </div>

    <style>
        .order-send-status .item {
            cursor: pointer;
            filter: grayscale(1);
        }
        .order-send-status .item.active {
            filter: grayscale(0);
        }
    </style>
    <script>
        $(document).on('click', '.submit-status-form', function () {
            $(this).parent().submit();
        });
        function changeStatus(response) {
            if (response.status == 'success') {
                var form = $('#s-'+response.shippingId+'-s-'+response.statusCode);
                if (response.active) {
                    form.find('.time').text(response.dateTime);
                    form.addClass('active');
                } else {
                    form.find('.time').text('');
                    form.removeClass('active');
                }
            } else {
                iziToast.error({
                    message: response.message
                })
            }
        }
    </script>

@endsection
