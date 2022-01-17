@extends("admin.{$adminTheme}.master")
@section('title', 'مجوز ها')

@section('content')

    <div class="col-12">

        <div class="box-solid box-primary">

            <div class="box-header">
                <h3 class="box-title">@yield('title')</h3>
                <a title="افزودن" href="{{ route('admin.permissions.create') }}" class="btn-icon icon-add btn-icon-warning tooltip"></a>
                <a title="بروزرسانی" href="{{ route('admin.permissions.sync') }}" class="btn-icon icon-sync btn-icon-pink tooltip"></a>
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
                            <th>عنوان</th>
                            <th>مجوز</th>
                            <th>عملیات</th>
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
                                <td>
                                    <a class="btn-icon btn-icon-success icon-pencil toolip" title="ویرایش"
                                       href="{{ route('admin.permissions.edit', ['id' => $permission->id]) }}"></a>
                                    <a class="btn-icon btn-icon-danger icon-delete toolip" title="حذف"
                                       onclick="destroyRow('users/permissions', '{{ $permission->id }}', this)"></a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>

                <div class="tac pt15">
                    {!! $permissions->render() !!}
                </div>

            </div>

            <div class="box-footer tal">
                <a href="{{ route('admin.permissions.create') }}" class="btn-lg btn-primary">افزودن مجوز</a>
            </div>

        </div>

    </div>

@endsection
