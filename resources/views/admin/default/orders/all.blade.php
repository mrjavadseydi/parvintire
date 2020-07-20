@extends("admin.{$adminTheme}.master")
@section('title', 'سفارشات')

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
                            <th>مبلغ (تومان)</th>
                            <th>کد رهگیری</th>
                            <th>وضعیت</th>
                            <th>تاریخ</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>

                        @php
                            $i = $records->perPage() * ($records->currentPage() - 1);
                        @endphp

                        @foreach($records as $record)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>#{{ $record->id }}</td>
                                <td>{{ \App\User::where('id', $record->user_id)->first()->name() }}</td>
                                <td class="color-green">{{ $record->price() }}</td>
                                <td class="color-orange">{{ $record->referenceId() }}</td>
                                <td style="color: {{ config("status.orderStatus.{$record->status}.color") }}; background-color: {{ config("status.orderStatus.{$record->status}.lightColor") }};">{{ config("status.orderStatus.{$record->status}.title") }}</td>
                                <td class="ltr">{{ $record->createdAt() }}</td>
                                <td>
                                    @if($record->status == 1)
                                        <a class="btn-icon btn-icon-info icon-visibility toolip" title="مشاهده" href="{{ url("admin/orders/show/{$record->id}") }}"></a>
                                    @endif
                                    <a class="btn-icon btn-icon-success icon-pencil toolip" title="ویرایش" href="{{ url("admin/users/{$record->id}/edit") }}"></a>
{{--                                    <a class="btn-icon btn-icon-danger icon-delete toolip" title="حذف" onclick="destroyRow('users/destroy', '{{ $record->id }}', this)"></a>--}}
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>

                <div class="tac pt15">
                    {!! $records->render() !!}
                </div>

            </div>

            <div class="box-footer tal">
                <a href="{{ route('admin.users.roles.create') }}" class="btn-lg btn-primary">افزودن نقش جدید</a>
            </div>

        </div>

    </div>

@endsection
