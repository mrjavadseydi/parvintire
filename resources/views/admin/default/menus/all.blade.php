@extends("admin.{$adminTheme}.master")
@section('title', 'فهرست ها')

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

                <div class="dragscroll responsive-table">
                    <table class="tac">
                        <thead>
                        <tr>
                            <th></th>
                            <th>عنوان</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>

                        @php
                            $i = $menus->perPage() * ($menus->currentPage() - 1);
                        @endphp

                        @foreach($menus as $record)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $record->title }}</td>
                                <td>
                                    <a class="btn-icon btn-icon-success icon-pencil toolip" title="ویرایش" href="{{ route('admin.menus.edit', $record) }}"></a>
                                    <a class="btn-icon btn-icon-danger icon-delete toolip" title="حذف" href="{{ route('admin.menus.destroy.confirm', $record) }}?url={{ url()->current() }}"></a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>

                <div class="tac pt15">
                    {!! $menus->render() !!}
                </div>

            </div>

        </div>

    </div>

@endsection
