@extends('admin.default.dashboard')
@section('content')

    <div class="row">
        <div class="col-md-4">
            <div class="box-solid box-info">
                <div class="box-header">
                    <h3>سفارشات اخیر</h3>
                </div>
                <div class="box-body">
                    <table>
                        <thead>
                        <tr>
                            <th>شماره سفارش	</th>
                            <th>کاربر</th>
                            <th>پرداختی</th>
                            <th>وضعیت سفارش</th>
                            <th>مشاهده</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(\LaraBase\Store\Models\Order::latest()->limit(20)->get() as $item)
                            <?php
                            $user = \LaraBase\Auth\Models\User::where('id', $item->user_id)->first();
                            ?>
                            <tr>
                                <td class="tac">{{ $item->id }}</td>
                                <td class="tac">{{ $user->fullname ?? '-' }}</td>
                                <td class="tac">{{ $item->price ?? '-' }}</td>
                                <td style="color: {{ config("store.orderStatus.{$item->status}.color") }}; background-color: {{ config("store.orderStatus.{$item->status}.lightColor") }};">{{ config("store.orderStatus.{$item->status}.title") }}</td>
                                <td class="tac">
                                    <a class="btn-icon btn-icon-success icon-pencil toolip" title="مشاهده" href="{{ route("admin.orders.edit", $item) }}"></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <div class="col-md-4">
            <div class="box-solid box-info">
                <div class="box-header">
                    <h3> تیکت های اخیر </h3>
                </div>
                <div class="box-body">
                    <table>
                        <thead>
                        <tr>
                            <th>نام و نام خانوادگی</th>
                            <th> ایمیل </th>
                            <th>موضوع</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(\LaraBase\Comments\Models\Comment::latest()->where('type', '2')->limit(20)->get() as $item)
                            <?php
                            $user = \LaraBase\Auth\Models\User::where('id', $item->user_id)->first();
                            if ($user == null) {
                                $name = 'کاربر مهمان';
                            } else {
                                $name = $user->fullname ?? '-';
                            }
                            ?>
                            <tr>
                                <td class="tac">{{ $name }}</td>
                                <td class="tac">{{ $item->email ?? '-' }}</td>
                                <td class="tac">{{ $item->subject ?? '-' }}</td>
                                <td class="tac">
                                    <a class="btn-icon btn-icon-info icon-eye toolip" title="مشاهده" href="{{ route('admin.tickets.edit', ['id' => $item->id]) }}"></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="box-solid box-info">
                <div class="box-header">
                    <h3>کاربران ثبت نام شده اخیر</h3>
                </div>
                <div class="box-body">
                    <table>
                        <thead>
                        <tr>
                            <th>شناسه</th>
                            <th>نام و نام خانوادگی</th>
                            <th>مشاهده</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(\LaraBase\Auth\Models\User::latest()->limit(20)->get() as $item)
                            <tr>
                                <td class="tac">{{ $item->id }}</td>
                                <td class="tac">{{ $item->fullname ?? '-' }}</td>
                                <td class="tac">
                                    <a class="btn-icon btn-icon-info icon-eye toolip" title="مشاهده" href="{{ url('admin/users/' . $item->id . '/edit') }}"></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>





        <div class="col-md-12">
            <div class="box">

                <div class="box-header">
                    <h3 class="box-title"> قیمت محصولات روزانه </h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">

                    <form method="POST" action="{{ url('upload/dailyPriceList') }}" enctype="multipart/form-data">
                        @csrf
                        <label for="daily_price_list">آپلود فایل لیست قیمت ها</label>
                        <input type="file" name="file" id="daily_price_list" value="Enter file"><br>
                        <input type="submit" value="ارسال" class="btn btn-success">
                    </form>
                </div>
                <!-- /.box-body -->
            </div>
        </div>




    </div>
@endsection
