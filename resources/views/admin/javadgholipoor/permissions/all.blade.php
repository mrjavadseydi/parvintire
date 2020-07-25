@extends("admin.{$adminTheme}.master")
@section('title', 'مجوز ها')
@section('buttons')
    <a href="{{ route('admin.permissions.sync') }}" class="btn btn-outline-primary"><i class="fad fa-sync-alt align-middle ml-2"></i>همگام سازی</a>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary"><i class="fad fa-users align-middle ml-2"></i>کاربران</a>
    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-primary"><i class="fad fa-user-tie align-middle ml-2"></i>نقش ها</a>
@endsection
@section('content')

    <div class="card">

        <div class="card-body">

            <div class="table-responsive">
                <table>
                    <thead>
                    <tr>
                        <th></th>
                        <th>عنوان</th>
                        <th>مجوز</th>
                    </tr>
                    </thead>
                    <tbody>

                    @php
                        $i = $permissions->perPage() * ($permissions->currentPage() - 1);
                    @endphp

                    @foreach($permissions as $permission)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $permission->label }}</td>
                            <td>{{ $permission->name }}</td>
{{--                                <td>--}}
{{--                                    <a class="btn-icon btn-icon-success icon-pencil toolip" title="ویرایش"--}}
{{--                                       href="{{ route('admin.permissions.edit', ['id' => $permission->id]) }}"></a>--}}
{{--                                    <a class="btn-icon btn-icon-danger icon-delete toolip" title="حذف"--}}
{{--                                       onclick="destroyRow('users/permissions', '{{ $permission->id }}', this)"></a>--}}
{{--                                </td>--}}
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>

        </div>

        <div class="text-center ltr">
            {!! $permissions->render() !!}
        </div>

    </div>

@endsection
