@extends("admin.{$adminTheme}.master")
@section('title', 'نقش ها')
@section('buttons')
    <a href="{{ route('admin.roles.create') }}" class="btn btn-outline-primary"><i class="far fa-plus align-middle ml-2"></i>افزودن نقش</a>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary"><i class="fad fa-users align-middle ml-2"></i>کاربران</a>
    <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-primary"><i class="fad fa-user-tie align-middle ml-2"></i>مجوز ها</a>
@endsection
@section('content')

    <div class="card">

        <div class="card-body">

            <div class="responsive-table">
                <table class="tac">
                    <thead>
                    <tr>
                        <th></th>
                        <th>عنوان</th>
                        <th>نقش</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>

                    @php
                        $i = $roles->perPage() * ($roles->currentPage() - 1);
                    @endphp

                    @foreach($roles as $role)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $role->label }}</td>
                            <td>{{ $role->name }}</td>
                            <td>
                                <a class="jgh-tooltip fa fa-edit text-success h5" title="ویرایش" href="{{ route('admin.roles.edit', ['id' => $role->id]) }}"></a>
                                <a class="jgh-tooltip fa fa-trash-alt text-danger h5" title="حذف" onclick="destroyRow('users/roles', '{{ $role->id }}', this)"></a>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>

            <div class="tac pt15">
                {!! $roles->render() !!}
            </div>

        </div>

    </div>

@endsection
