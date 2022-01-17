@extends("admin.{$adminTheme}.master")
@section('title', 'مشاهده تراکنش')

@section('content')
    <div class="box box-teal">

        <div class="box-header">
            <h3 class="box-title">اطلاعات تراکنش شماره {{$transaction->id}}</h3>
        </div>

        <div class="box-body">

            <div class="responsive-table">
                <table class="tac">
                    <thead>
                    <tr>
                        <th>مشخصه</th>
                        <th>مقدار</th>
                    </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td>کاربر</td>
                            <td>{{ $transaction->user->name() }}</td>
                        </tr>
                        <tr>
                            <td>سفارش</td>
                            <td><a href="{{route('admin.orders.edit', ['order' => $transaction->relation_id])}}">{{ $transaction->relation_id }}</a></td>
                        </tr>
                        <tr>
                            <td>درگاه</td>
                            <td>{{ $transaction->gateway ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>مبلغ</td>
                            <td>{{ number_format($transaction->price) }}</td>
                        </tr>
                        <tr>
                            <td>وضعیت</td>
                            <td style="color: {{ config("transaction.status.{$transaction->status}.color") }}; background-color: {{ config("transaction.status.{$transaction->status}.lightColor") }};">{{ config("transaction.status.{$transaction->status}.title") }}</td>
                        </tr>
                        <tr>
                            <td>کدرهگیری</td>
                            <td>{{ $transaction->reference_id ?? '-' }}</td>
                        </tr>

                        @php
                            $info = json_decode($transaction->information);
                        @endphp

                        <tr>
                            <td>کد پیگیری بانک</td>
                            <td dir="ltr">{{ $info->SaleOrderId ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>شماره کارت</td>
                            <td dir="ltr">{{ $info->CardHolderPan ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
        </div>

        <div class="box-footer">

        </div>

    </div>

@endsection
