@extends(includeTemplate('profile.master'))
@section('title', 'سفارشات من')
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">سفارشات من</li>
@endsection
@section('main')
    @if($orders->count() > 0)
    <div class="table-responsive iransansFa">
        <table class="table">
            <thead>
            <tr>
                <th class="text-center">شماره سفارش</th>
                <th class="text-center">تاریخ سفارش</th>
                <th class="text-center">وضعیت</th>
                <th class="text-center">تعداد کالا</th>
                <th class="text-center">مبلغ (تومان)</th>
                <th class="text-center">شماره پیگیری</th>
                <th class="text-center">عملیات</th>
            </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <?php $trans = $transactions->where('relation_id', $order->id)->first();?>
                    <tr>
                        <td class="text-center">{{ $order->id }}</td>
                        <td class="text-center">{{ jDateTime('Y/m/d', strtotime($order->created_at)) }}</td>
                        <td class="text-center"><span style="padding: 2px 5px; border-radius: 5px; color: white; background: {{ $status[$order->status]['color'] }};">{{ $status[$order->status]['title'] }}</span></td>
                        <td class="text-center">{{ $order->productsCounts() }}</td>
                        <td class="text-center">{{ $trans == null ? '-' : convertPrice($trans->price) }}</td>
                        <td class="text-center">{{ $trans == null ? '-' : $trans->reference_id }}</td>
                        <td class="text-center">
                            @if($order->status == 0)
                                <a href="{{ url('cart') }}" class="btn btn-success">پرداخت</a>
                            @else
                                <a href="{{ url('profile/orders/' . $order->id) }}">مشاهده سفارش</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination-rtl">
            {{ $orders->render() }}
        </div>
    </div>
    @else
        <div class="alert alert-info text-center">
            <h4>شما هیچ سفارشی ندارید</h4>
        </div>
    @endif
@endsection
