@extends("admin.{$adminTheme}.master")
@section('title', 'نقش ها')

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
                                    <a class="btn-icon btn-icon-success icon-pencil toolip" title="ویرایش" href="{{ route('admin.roles.edit', ['id' => $role->id]) }}"></a>
                                    <a class="btn-icon btn-icon-danger icon-delete toolip" title="حذف" onclick="destroyRow('users/roles', '{{ $role->id }}', this)"></a>
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

            <div class="box-footer tal">
                <a href="{{ route('admin.roles.create') }}" class="btn-lg btn-primary">افزودن نقش جدید</a>
            </div>

        </div>

    </div>

@endsection
