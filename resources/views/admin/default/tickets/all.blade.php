@extends("admin.{$adminTheme}.master")
@section('title', 'تیکت ها')

@section('content')

    <div class="col-12">

        <div class="box-solid box-primary">

            <div class="box-header">
                <h3 class="box-title">@yield('title')</h3>
                <div class="box-tools">
                    <span class="filters-open btn btn-danger ml10">فیلتر</span>
                    <i class="box-tools-icon icon-minus"></i>
                </div>
            </div>

            <div class="box-body">

                <form action="" method="get">

                    <input type="hidden" name="filters" value="true">
                    <div class="row filters">

                        <div class="col-lg-12">
                            @include('filters.default.search', ['placeholder' => 'شناسه، نام، نام خانوادگی، موبایل، ایمیل'])
                        </div>

                        <div class="col-12 mt5 tal">
                            <button class="btn-lg btn-success">اعمال فیلتر</button>
                        </div>

                        @include('filters.default.script')

                    </div>

                </form>

                <div class="dragscroll responsive-table">
                    <table class="tac">
                        <thead>
                        <tr>
                            <th></th>
                            <th>کاربر</th>
                            <th>نام</th>
                            <th>موضوع</th>
                            <th>موبایل</th>
                            <th>ایمیل</th>
                            <th>بخش</th>
                            <th>اهمیت</th>
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
                                <td>{{ ($record->user_id == null ? '-' : \LaraBase\Auth\Models\User::find($record->user_id)->name()) }}</td>
                                <td>{{ $record->name ?? '-' }}</td>
                                <td>{{ $record->subject }}</td>
                                <td>{{ $record->mobile ?? '-' }}</td>
                                <td>{{ $record->email ?? '-' }}</td>
                                <td>{{ $record->department ?? '-' }}</td>
                                <td>{{ $record->priority ?? '-' }}</td>
                                <td>{{ config("comment.status.ticket.{$record->status}.title") }}</td>
                                <td class="ltr">{{ jDateTime('Y/m/d H:i:s', strtotime($record->created_at)) }}</td>
                                <td>
                                    <a class="btn-icon btn-icon-info icon-eye toolip" title="مشاهده" href="{{ route('admin.tickets.edit', ['id' => $record->id]) }}"></a>
{{--                                    <a class="btn-icon btn-icon-danger icon-delete toolip" title="حذف" href="{{ route('admin.tickets.destroy.confirm', ['id' => $record->id]) }}?url={{ url()->current() }}"></a>--}}
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

        </div>

    </div>

@endsection
