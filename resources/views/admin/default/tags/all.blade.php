@extends("admin.{$adminTheme}.master")
@section('title', 'برچسب ها')

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
                            <th>برچسب</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>

                        @php
                            $i = $tags->perPage() * ($tags->currentPage() - 1);
                        @endphp

                        @foreach($tags as $tag)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $tag->tag }}</td>
                                <td>
                                    <a class="btn-icon btn-icon-success icon-pencil toolip" title="ویرایش"
                                       href="{{ route('admin.tags.edit', ['id' => $tag->id]) }}"></a>
                                    <a href="{{ route('admin.tags.destroy.confirm', $tag) }}?url={{ url()->current() }}" class="btn-icon btn-icon-danger icon-delete toolip" title="حذف"></a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>

                <div class="tac pt15">
                    {!! $tags->render() !!}
                </div>

            </div>

            <div class="box-footer tal">
                <a href="{{ route('admin.tags.create') }}" class="btn-lg btn-primary">افزودن برچسب</a>
            </div>

        </div>

    </div>

@endsection
