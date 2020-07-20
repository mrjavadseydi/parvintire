@extends("admin.{$adminTheme}.master")
@section('title', 'کاربران')

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
                            <th>نام</th>
                            <th>موبایل</th>
                            <th>ایمیل</th>
{{--                            <th>مطالب</th>--}}
                            <th>نقش‌ها</th>
                            <th>آخرین‌بازدید</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>

                        @php
                            $i = $users->perPage() * ($users->currentPage() - 1);
                        @endphp

                        @foreach($users as $record)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $record->name . ' ' . $record->family }}</td>
                                <td>{{ $record->mobile }}</td>
                                <td>{{ $record->email }}</td>
{{--                                <td><a target="_blank" class="color-primary" href="{{ url("admin/posts?search=&users[]={$record->id}&filters=true") }}">{{ $record->posts->count() }}</a></td>--}}
                                <td style="max-width: 350px;">
                                    <ul class="tag-view">
                                        @foreach($record->roles() as $role)
                                            <li>{{ $role->label }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>{{ $record->lastSeen() }}</td>
                                <td>
                                    <a class="btn-icon btn-icon-success icon-pencil toolip" title="ویرایش" href="{{ url("admin/users/{$record->id}/edit") }}"></a>
                                    <a class="btn-icon btn-icon-warning icon-account_balance_wallet toolip" title="افزایش اعتبار کیف‌پول"></a>
                                    <a class="btn-icon btn-icon-purple icon-card_membership toolip" href="{{ url("/switch/user/{$record->id}") }}" title="گرفتن سطح دسترسی"></a>
                                    @if(empty($record->email_verified_at))
                                        <a class="btn-icon btn-icon-orange icon-mail_outline toolip" title="ایمیل تایید نشده" href="{{ route('admin.users.verify', ['type' => 'email', 'id' => $record->id]) }}"></a>
                                    @endif
                                    @if(empty($record->mobile_verified_at))
                                        <a class="btn-icon btn-icon-teal icon-mobile toolip" title="موبایل تایید نشده" href="{{ route('admin.users.verify', ['type' => 'mobile', 'id' => $record->id]) }}"></a>
                                    @endif
                                    @if(!empty($record->email_verified_at) && !empty($record->mobile_verified_at))
                                        <a class="btn-icon btn-icon-info icon-verified_user toolip" title="کاربر فعال است"></a>
                                    @endif
                                    <a class="btn-icon btn-icon-danger icon-delete toolip" title="حذف" onclick="destroyRow('users/destroy', '{{ $record->id }}', this)"></a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>

                <div class="tac pt15">
                    {!! $users->render() !!}
                </div>

            </div>

        </div>

    </div>

@endsection
