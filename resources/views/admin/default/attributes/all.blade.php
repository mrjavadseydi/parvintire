@extends("admin.{$adminTheme}.master")
@section('title', 'ویژگی ها')

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

                @include('admin.default.languages', ['action' => url('admin/attributes/translate')])

                <div class="responsive-table">
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
                                <td>{{ $record->title }}</td>
                                <td>{{ $record->type }}</td>
                                <td>
                                    <a class="btn-icon btn-icon-success icon-pencil toolip" title="ویرایش"
                                       href="{{ route('admin.attributes.edit', ['id' => $record->id]) }}"></a>
                                    <a href="{{ route('admin.attributes.destroy.confirm', $record) }}?url={{ url()->current() }}" class="btn-icon btn-icon-danger icon-delete toolip" title="حذف"></a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>

                <div class="tac pt15">
                    {!! $records->appends(request()->input())->links() !!}
                </div>

            </div>

        </div>

    </div>

@endsection
