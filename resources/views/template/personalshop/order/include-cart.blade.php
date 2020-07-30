@if(isset($cart['carts']))
    @if($cart['carts']->count() > 0)
        <script src="{{ asset('assets/admin/default/store.js') }}"></script>
        <div class="cart-item active">
            @foreach($cart['shippings'] as $shippingId => $values)
                <?php
                $shipping = $values['shipping'];
                $orderShipping = $values['orderShipping'];
                if (isset($values['carts'])) {
                $carts = $values['carts'];
                $count = 0;
                foreach ($carts as $c) {
                    $count += $c['cart']->count;
                }
                ?>
                <div class="carts mt-3">
                    <div class="shipping mb-2 row">
                        <div class="col-6">
                            <i class="fa fa-shipping-fast"></i>
                            <h4 class="d-inline-block">{{ $shipping->title }}</h4>
                            <h6 class="d-inline-block">({{ $count }}کالا)</h6>
                        </div>
                        <div class="col-6 text-left">
                            <h6 class="font-weight-normal d-inline-block">{{ $shipping->description }}</h6>
                        </div>
                    </div>
                    <div class="products py-2">
                        <div class="table-responsive">
                            <table class="bg-white">
                                <thead>
                                <tr>
                                    <th>تصویر</th>
                                    <th>شرح محصول</th>
                                    <th>تعداد</th>
                                    <th>قیمت واحد (تومان)</th>
                                    <th>تخفیف (تومان)</th>
                                    <th>قیمت کل‌ (تومان)</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($carts as $cartItem)
                                    <?php
                                    $c = $cartItem['cart'];
                                    $product = $cartItem['product'];
                                    $post = $cartItem['post'];
                                    $href = $post->href();
                                    $price = $product->price();
                                    $discount = $product->discount();
                                    ?>
                                    <tr>
                                        <td>
                                            <a href="{{ $href }}">
                                                <figure class="mb-0 py-2">
                                                    <img src="{{ $post->thumbnail(80, 80) }}" alt="{{ $product->title }}">
                                                </figure>
                                            </a>
                                        </td>
                                        <td>
                                            <a class="mb-auto" href="{{ $href }}">
                                                <h1 class="mb-0">{{ $product->title }}</h1>
                                            </a>
                                        </td>
                                        <td>
                                            <div class="counter">
                                                <form class="d-inline-block addToCart" method="post" action="{{ route('addToCart') }}?page=cart">
                                                    <input type="hidden" name="productId" value="{{ $product->product_id }}">
                                                    <input type="hidden" name="view1" value="order.cart-header">
                                                    <input type="hidden" name="view2" value="order.include-cart">
                                                    <button class="plus fal fa-plus"></button>
                                                </form>
                                                <button>{{ $c->count }}</button>
                                                <form class="d-inline-block deleteFromCart" method="post" action="{{ route('deleteFromCart') }}?page=cart">
                                                    <input type="hidden" name="productId" value="{{ $product->product_id }}">
                                                    <input type="hidden" name="view1" value="order.cart-header">
                                                    <input type="hidden" name="view2" value="order.include-cart">
                                                    @if($c->count > 1)
                                                        <button class="mines fal fa-minus"></button>
                                                    @else
                                                        <button class="mines far fa-trash-alt text-danger"></button>
                                                    @endif
                                                </form>
                                            </div>
                                        </td>
                                        <td>
                                            @if($discount > 0)
                                                {{ number_format($price) }}
                                                <del class="d-block text-muted">{{ number_format($price+$discount) }}</del>
                                            @else
                                                {{ number_format($price) }}
                                            @endif
                                        </td>
                                        <td class="text-danger">
                                            @if($discount > 0)
                                                {{ number_format($c->count * $discount) }}
                                            @else
                                                0
                                            @endif
                                        </td>
                                        <td class="text-success">
                                            @if($discount > 0)
                                                {{ number_format($c->count * convertPrice($c->price)) }}
                                                <del class="d-block text-muted">{{ number_format(convertPrice(($c->price+$c->discount) * $c->count)) }}</del>
                                            @else
                                                {{ number_format($c->count * convertPrice($c->price)) }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php
                }
                ?>
            @endforeach
            <div class="row bg-white">
                <div class="col-md-8"></div>
                <div class="col-md-4">
                    @include(includeTemplate('order.payment-info'))
                    @if(auth()->check())
                        <div class="text-left mt-2">
                            <a class="btn btn-success text-white px-4 py-2" href="{{ url('cart/address') }}">تایید و ثبت آدرس</a>
                        </div>
                    @else
                        <div class="text-left mt-2">
                            <h6 class="card p-3">
                                کابر گرامی لطفا جهت ثبت سفارش ابتدا در سایت عضو شوید و یا وارد سایت شوید
                            </h6>
                            <div class="d-flex pt-2">
                                <a href="{{ route('register') }}" class="w-50 py-2 btn btn-sm btn-success">ثبت نام</a>
                                <a href="{{ route('login') }}" class="w-50 py-2 btn btn-sm btn-primary mr-3">ورود به سایت</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @else
        <div class="text-center col-12 m-0">
            <figure class="text-center">
                <img src="{{ image('empty-cart.png', 'template') }}" alt="سبد خرید شما خالی است">
                <figcaption class="text-center pt-3"><h5 class="text-center">سبد خرید شما خالی است</h5></figcaption>
            </figure>
        </div>
    @endif
@else
    <div class="text-center col-12 m-0">
        <figure class="text-center">
            <img src="{{ image('empty-cart.png', 'template') }}" alt="سبد خرید شما خالی است">
            <figcaption class="text-center pt-3"><h5 class="text-center">سبد خرید شما خالی است</h5></figcaption>
        </figure>
    </div>
@endif