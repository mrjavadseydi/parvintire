@extends(includeTemplate('master'))
@section('content')
    <?php
        $href = url('/');
        $text = 'بازگشت به خانه';
        if ($transaction->relation == 'order') {
            $href = url('cart/payment');
            $text = 'بازگشت به صفحه پرداخت';
        } else if ($transaction->relation == 'course') {
            $post = \LaraBase\Posts\Models\Post::find($transaction->relation_id);
            $href = $post->href();
            $text = 'بازگشت به صفحه قبل';
        }
    ?>
    <div class="container py-3 d-flex justify-content-center">
        <div class="payment rounded failed">
            <div class="header position-relative">
                <h4>تراکنش ناموفق</h4>
                <div class="abs">
                    <i class="fa fa-times"></i>
                </div>
            </div>
            <div class="body p-4">
                <h5 class="text-center text-danger py-3">متاسفانه پرداخت با موفقیت انجام نشد</h5>
                <div class="d-flex justify-content-center">
                    <a href="{{ $href }}" class="btn btn-warning ml-2">{{ $text }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection
