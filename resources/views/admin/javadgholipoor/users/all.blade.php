@extends("admin.{$adminTheme}.master")
{{--@section('title', 'کاربران')--}}
{{--@section('filter', true)--}}
{{--@section('buttons')--}}
{{--    <a href="{{ route('admin.users.create') }}" class="btn btn-outline-primary"><i class="far fa-plus align-middle ml-2"></i>افزودن کاربر</a>--}}
{{--    <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-primary"><i class="far fa-badge-check align-middle ml-2"></i>مجوز ها</a>--}}
{{--    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-primary"><i class="fad fa-user-tie align-middle ml-2"></i>نقش ها</a>--}}
{{--@endsection--}}
@section('content')

    <div class="card">

        <div class="card-body">

            <div class="table-responsive">
                <table>
                    <thead>
                    <tr>
                        <th></th>
                        <th>نام</th>
                        <th>نام کاربری</th>
                        <th>موبایل</th>
                        <th>ایمیل</th>
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
                            <td>{{ $record->name() }}</td>
                            <td>{{ $record->username ?? '-' }}</td>
                            <td>{{ $record->mobile ?? '-' }}</td>
                            <td>{{ $record->email ?? '-' }}</td>
                            <td style="max-width: 350px;">
                                @foreach($record->roles() as $role)
                                    <span class="btn btn-outline-secondary">{{ $role->label }}</span>
                                @endforeach
                            </td>
                            <td class="ltr">{{ $record->lastSeen() }}</td>
                            <td>
                                <a class="jgh-tooltip fa fa-edit text-success h5 ml-1" title="ویرایش" href="{{ route('admin.users.edit', $record) }}"></a>
                                <a class="jgh-tooltip fa fa-sign-in text-purple h5 ml-1" href="{{ url("/switch/user/{$record->id}") }}" title="گرفتن سطح دسترسی"></a>
                                @if(empty($record->email_verified_at))
                                    <a class="jgh-tooltip fa fa-envelope text-warning h5 ml-1" title="ایمیل تایید نشده" href="{{ route('admin.users.verify', ['type' => 'email', 'id' => $record->id]) }}"></a>
                                @endif
                                @if(empty($record->mobile_verified_at))
                                    <a class="jgh-tooltip fa fa-mobile text-warning h5 ml-1" title="موبایل تایید نشده" href="{{ route('admin.users.verify', ['type' => 'mobile', 'id' => $record->id]) }}"></a>
                                @endif
                                @if(!empty($record->email_verified_at) && !empty($record->mobile_verified_at))
                                    <a class="jgh-tooltip fa fa-badge-check text-info h5 ml-1" title="کاربر فعال است"></a>
                                @endif
                                {{ delete(['action' => route('admin.users.destroy', $record), 'title' => $record->name()]) }}
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>

            <div class="text-center ltr">
                {!! $users->render() !!}
            </div>

        </div>

    </div>

@endsection
