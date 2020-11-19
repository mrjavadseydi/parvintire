<div>
    <?php
    $colorAttributes = explode(',', getOption('digishopColorAttributes'));
    $productAttribute = $products['productAttributes'];
    $activeAttributes = [];
    $activeKeys = [];
    foreach ($productAttribute as $items) {
        foreach ($items as $aId => $kId) {
            $activeAttributes[$aId] = $aId;
            $activeKeys[$kId] = $kId;
        }
    }
    ?>
    <form id="get-product-form" action="{{ route('getProduct') }}" method="post" class="attributes border-bottom pb-3 pb-md-5">
        @csrf
        <input type="hidden" name="postId" value="{{ $post->id }}">
        <input type="hidden" name="view1" value="pages.product.add">
        @foreach($products['attributes'] as $attribute)
            @if(isset($activeAttributes[$attribute['id']]))
                <?php $selectedKey = $productAttribute[$product->product_id][$attribute['id']] ?? ''; ?>
                @if(in_array($attribute['id'], $colorAttributes))
                    <div class="colors pt-3 pt-md-5">
                        <h5 class="mb-2">{{ $attribute['title'] }}</h5>
                        @foreach($attribute['keys'] as $key)
                            @if(isset($activeKeys[$key['id']]))
                                <label class="{{ $key['id'] == $selectedKey ? 'active' : '' }}" for="a-{{ $attribute['id'] }}-k-{{ $key['id'] }}">
                                    <input {{ checked($key['id'], $selectedKey) }} class="submit-get-product-form d-none" id="a-{{ $attribute['id'] }}-k-{{ $key['id'] }}" type="radio" name="attributes[{{ $attribute['id'] }}]" value="{{ $attribute['id'] .'-' . $key['id'] }}">
                                    <span style="background: {{ $key['record']->description }};"></span>
                                </label>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div class="buttons pt-3 pt-md-5">
                        <h5 class="mb-2">{{ $attribute['title'] }}</h5>
                        @foreach($attribute['keys'] as $key)
                            @if(isset($activeKeys[$key['id']]))
                                <label class="mb-2 {{ $key['id'] == $selectedKey ? 'active' : '' }}" for="a-{{ $attribute['id'] }}-k-{{ $key['id'] }}">
                                    <input {{ checked($key['id'], $selectedKey) }} class="submit-get-product-form d-none" type="radio" id="a-{{ $attribute['id'] }}-k-{{ $key['id'] }}" name="attributes[{{ $attribute['id'] }}]" value="{{ $attribute['id'] .'-' . $key['id'] }}">
                                    <span>{{ $key['title'] }}</span>
                                </label>
                            @endif
                        @endforeach
                    </div>
                @endif
            @endif
        @endforeach
    </form>
    <form id="single-add-to-cart-form" method="post" action="{{ route('addToCart') }}" class="flex-wrap flex-md-nowrap cart-view-2 addToCart pricing d-flex justify-content-center justify-content-md-between align-items-end py-3">
        @csrf
        @include(includeTemplate('pages.product.add-to-cart'))
    </form>
    <div class="unstock py-3 d-none">
       <div class="alert alert-info">
           <p>محصول انتخابی موجود نیست</p>
       </div>
    </div>
</div>
<script>
    $('#get-product-form').ajaxForm({
        complete: function (data) {
            var response = $.parseJSON(data.responseText);
            if(response.status == 'success') {
                $('#add-to-cart').html(response.html1);
                if(response.discount > 0) {
                    $('#product-percent').text(response.percent+'% تخفیف').removeClass('d-none');
                } else {
                    $('#product-percent').addClass('d-none');
                }
            } else {
                $('.unstock').removeClass('d-none');
                $('.pricing').addClass('d-none').removeClass('d-flex');
                $('#product-percent').addClass('d-none');
            }
        },
        error: function (data) {

        }
    });
</script>
<script src="{{ asset('assets/admin/default/store.js') }}"></script>
