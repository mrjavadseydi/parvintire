@extends("admin.{$adminTheme}.master")
@section('title', 'نوع مطالب')

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
                                <th>نوع</th>
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
                                <td>{{ $record->label }}</td>
                                <td>{{ $record->type }}</td>
                                <td>
                                    <a class="btn-icon btn-icon-success icon-pencil toolip" title="ویرایش" href="{{ route('admin.post-types.edit', $record) }}"></a>
                                    <a href="{{ route('admin.post-types.destroy.confirm', $record) }}?url={{ url()->current() }}" class="btn-icon btn-icon-danger icon-delete toolip" title="حذف" onclick=""></a>
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
