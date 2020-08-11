@extends(includeTemplate('master'))
@section('content')
    <?php
        $href = url('/');
        $text = 'مشاهده فاکتور';
        if ($transaction->relation == 'order') {
            $href = url('profile/orders/'.$transaction->relation_id);
        } else if ($transaction->relation == 'course') {
            $post = \LaraBase\Posts\Models\Post::find($transaction->relation_id);
            $href = $post->href();
            $text = 'برگشت و دانلود فایل ها';
        }
    ?>
    <div class="container py-3 d-flex justify-content-center">
        <div class="payment rounded success">
            <div class="header position-relative">
                <h4>تراکنش موفق</h4>
                <div class="abs">
                    <i class="fa fa-check"></i>
                </div>
            </div>
            <div class="body p-4">
                <h5 class="text-center">پرداخت با موفقیت انجام گردید</h5>
                <div class="py-4">
                    <div class="d-flex mb-3">
                        <span class="w-50">شماره رهگیری</span>
                        <span class="w-50">{{ $transaction->reference_id }}</span>
                    </div>
                    <div class="d-flex mb-3">
                        <span class="w-50">مبلغ تراکنش</span>
                        <span class="w-50">{{ number_format(convertPrice($transaction->price)) }} تومان</span>
                    </div>
                    <div class="d-flex mb-3">
                        <span class="w-50">پرداخت کننده</span>
                        <span class="w-50">{{ \LaraBase\Auth\Models\User::find($transaction->user_id)->name() }}</span>
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <a href="{{ url('/') }}" class="btn btn-outline-warning ml-2">برگشت به خانه</a>
                    <a href="{{ $href }}" class="btn btn-outline-warning">{{ $text }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection
