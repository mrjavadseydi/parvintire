@extends("admin.{$adminTheme}.master")
@section('title', $title)

@section('content')

    <div class="col-12">

        <div class="box-solid box-primary">

            <div class="box-header">
                <h3 class="box-title">@yield('title')</h3>
                <div class="box-tools">
                    <i class="box-tools-icon icon-minus"></i>
                </div>
            </div>

            <div class="box-body">

                <div class="responsive-table">
                    <table class="tac">
                        <thead>
                        <tr>
                            <th></th>
                            <th>شناسه</th>
                            <th>کاربر</th>
                            <th>مبلغ (ریال)</th>
                            <th>کد رهگیری</th>
                            <th>درگاه</th>
                            <th>وضعیت ارسال</th>
                            <th>تاریخ</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>

                        @php
                            $i = $records->perPage() * ($records->currentPage() - 1);
                        @endphp

                        @foreach($records as $record)
                            <?php
                                $user = $users->where('id', $record->user_id)->first();
                                $sendStatus = $record->shippingStatus->status ?? 1;
//                                $record->transactions
                            ?>
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>#{{ $record->id }}</td>
                                <td>{{ $user == null ? 'کاربر مهمان' : $user->name() }}</td>
                                <td class="color-green">{{ number_format($record->payed_price) }}</td>
                                <td class="color-orange">{{ '-' ?? '-' }}</td>
                                <td title="{{ $record->gateway }}"><img width="50px" src="{{ image("gateway/{$record->gateway}.png") }}" alt="{{ $record->gateway }}"></td>
                                <td style="color: {{ config("store.sendStatus.{$sendStatus}.color") }}; background-color: {{ config("store.sendStatus.{$sendStatus}.lightColor") }};">{{ config("store.sendStatus.{$sendStatus}.title") }}</td>
                                <td class="ltr">{{ jDateTime('Y/m/d H:i:s', strtotime($record->created_at)) }}{!! $record->updated_at == null ? '' : ($record->created_at != $record->updated_at ? '<br>' . jDateTime('Y/m/d H:i:s', strtotime($record->updated_at)) : '') !!}</td>
                                <td>
                                    <a class="btn-icon btn-icon-success icon-pencil toolip" title="مشاهده" href="{{ route("admin.orders.edit", $record) }}"></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="tac pt15">
                    {!! $records->appends($_GET)->links() !!}
                </div>

            </div>

        </div>

    </div>

@endsection
