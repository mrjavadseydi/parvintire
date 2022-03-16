<i class="fal fa-shopping-bag"></i>
<span class="badge"></span>
<span class="cart">
    <div class="header d-flex justify-content-between">
        <div>
            {{-- <span>مبلغ کل خرید : <b>{{ number_format(convertPrice($cart['payablePriceRial'])) }} تومان</b></span> --}}
        </div>
        <div>
            {{-- <a href="{{ url('cart') }}">مشاهده سبد خرید</a> --}}
        </div>
    </div>
    <div class="body scrollbar">
        @if($cart['carts'] != null)
            @foreach($cart['shippings'] as $shipping)
                @if(isset($shipping['carts']))
                    @foreach($shipping['carts'] as $item)
                        <div class="item py-2 d-flex">
                    <figure>
                        <img src="{{ $item['post']->thumbnail(50, 50) }}" alt="{{ $item['product']->title }}">
                    </figure>
                    <div class="d-flex flex-column justify-content-around px-2">
                        <h3 class="small">{{ $item['product']->title }}</h3>
                        <div class="text-muted">
                            <span>{{ $item['cart']->count }} عدد</span>
                            <span>|</span>
                            <span>{{ number_format($item['product']->price()) }} تومان</span>
                        </div>
                    </div>
                    <?php
                        $isCart = false;
                        $isPayment = false;
                        $get = 'single';
                        $url = str_replace(url(''), '', url()->current());

                        if (isset($_GET['page'])) {
                            if ($_GET['page'] == 'cart') {
                                $get = 'cart';
                                $isCart = true;
                            }
                        } else {
                            if (strpos($url, 'cart')) {
                                $get = 'cart';
                                $isCart = true;
                            }
                        }
                        if ($isCart) {
                            if (isset($_GET['payment'])) {
                                $isPayment = true;
                                $get = 'cart&payment=true';
                            } else {
                                if (strpos($url, 'payment')) {
                                    $isPayment = true;
                                    $get = 'cart&payment=true';
                                }
                            }
                        }
                    ?>
                    <form class="deleteFromCart" method="post" action="{{ route('deleteFromCart') }}?page={{ $get }}">
                        @csrf
                        <input type="hidden" name="productId" value="{{ $item['product']->product_id }}">
                        <input type="hidden" name="view1" value="order.cart-header">
                        @if($isCart)
                            @if($isPayment)
                                <input type="hidden" name="view2" value="order.payment-view">
                            @else
                                <input type="hidden" name="view2" value="order.include-cart">
                            @endif
                        @else
                            <input type="hidden" name="view2" value="pages.product.add-to-cart">
                        @endif
                        @if($item['cart']->count > 1)
                            <button class="header-cart-remove-{{ $item['product']->product_id }} fa fa-minus remove"></button>
                        @else
                            <button class="header-cart-remove-{{ $item['product']->product_id }} fa fa-trash-alt remove"></button>
                        @endif
                    </form>
                </div>
                    @endforeach
                @else
                    <figure class="text-center">
                        <img width="75%" src="{{ image('empty-cart.png', 'template') }}" alt="سبد خرید خالی است">
                    </figure>
                @endif
            @endforeach
        @else
            <figure class="text-center">
                <img width="75%" src="{{ image('empty-cart.png', 'template') }}" alt="سبد خرید خالی است">
                <figcaption class="text-center py-3 small"><h5 class="text-center">سبد خرید شما خالی است</h5></figcaption>
            </figure>
        @endif
    </div>
    <div class="footer">
        <a href="{{ url('cart') }}">تسویه حساب</a>
    </div>
</span>
<script src="{{ asset('assets/admin/default/store.js') }}"></script>
