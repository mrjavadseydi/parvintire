@extends(includeTemplate('master'))
@section('title', 'سبدخرید')
@section('content')
    <div class="cart py-3">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">صفحه اصلی</a></li>
                    <li class="breadcrumb-item active" aria-current="page">سبدخرید</li>
                </ol>
            </nav>
            <div class="cart-view-2">
                @include(includeTemplate('order.include-cart'))
            </div>
        </div>
    </div>
@endsection
