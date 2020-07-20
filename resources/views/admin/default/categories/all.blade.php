@extends("admin.{$adminTheme}.master")
@section('title', 'دسته بندی ها')

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
                            <th>مادر دسته</th>
                            <th>نوع</th>
                            <th>تعداد</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>

                        @php
                            $i = $categories->perPage() * ($categories->currentPage() - 1);
                        @endphp

                        @foreach(treeView($categories) as $category)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $category['title'] }}</td>
                                <td>{{ $category['record']->parentField() }}</td>
                                <td>{{ $category['record']->post_type }}</td>
                                <td><a target="_blankga" class="color-primary" href="{{ url("/admin/posts?filters=true&search=&categories[]={$category['id']}") }}">{{ $category['record']->posts->count() }}</a></td>
                                <td>
                                    <a class="btn-icon btn-icon-success icon-pencil toolip" title="ویرایش"
                                       href="{{ route('admin.categories.edit', ['id' => $category['id']]) }}"></a>
                                    <a href="{{ route('admin.categories.destroy.confirm', ['id' => $category['id']]) }}?url={{ url()->current() }}" class="btn-icon btn-icon-danger icon-delete toolip" title="حذف"></a>
                                </td>
                            </tr>

                            @if(isset($category['list']))
                                @foreach ($category['list'] as $category)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $category['title'] }}</td>
                                        <td>{{ $category['record']->parentField() }}</td>
                                        <td>{{ $category['record']->post_type }}</td>
                                        <td><a target="_blankga" class="color-primary" href="{{ url("/admin/posts?filters=true&search=&categories[]={$category['id']}") }}">{{ $category['record']->posts->count() }}</a></td>
                                        <td>
                                            <a class="btn-icon btn-icon-success icon-pencil toolip" title="ویرایش"
                                               href="{{ route('admin.categories.edit', ['id' => $category['id']]) }}"></a>
                                            <a href="{{ route('admin.categories.destroy.confirm', ['id' => $category['id']]) }}?url={{ url()->current() }}" class="btn-icon btn-icon-danger icon-delete toolip" title="حذف"></a>
                                        </td>
                                    </tr>
                                    @if(isset($category['list']))
                                        @foreach ($category['list'] as $category)
                                            <tr>
                                                <td>{{ ++$i }}</td>
                                                <td>{{ $category['title'] }}</td>
                                                <td>{{ $category['record']->parentField() }}</td>
                                                <td>{{ $category['record']->post_type }}</td>
                                                <td><a target="_blankga" class="color-primary" href="{{ url("/admin/posts?filters=true&search=&categories[]={$category['id']}") }}">{{ $category['record']->posts->count() }}</a></td>
                                                <td>
                                                    <a class="btn-icon btn-icon-success icon-pencil toolip" title="ویرایش"
                                                       href="{{ route('admin.categories.edit', ['id' => $category['id']]) }}"></a>
                                                    <a href="{{ route('admin.categories.destroy.confirm', ['id' => $category['id']]) }}?url={{ url()->current() }}" class="btn-icon btn-icon-danger icon-delete toolip" title="حذف"></a>
                                                </td>
                                            </tr>
                                            @if(isset($category['list']))
                                                @foreach ($category['list'] as $category)
                                                    <tr>
                                                        <td>{{ ++$i }}</td>
                                                        <td>{{ $category['title'] }}</td>
                                                        <td>{{ $category['record']->parentField() }}</td>
                                                        <td>{{ $category['record']->post_type }}</td>
                                                        <td><a target="_blankga" class="color-primary" href="{{ url("/admin/posts?filters=true&search=&categories[]={$category['id']}") }}">{{ $category['record']->posts->count() }}</a></td>
                                                        <td>
                                                            <a class="btn-icon btn-icon-success icon-pencil toolip" title="ویرایش"
                                                               href="{{ route('admin.categories.edit', ['id' => $category['id']]) }}"></a>
                                                            <a href="{{ route('admin.categories.destroy.confirm', ['id' => $category['id']]) }}?url={{ url()->current() }}" class="btn-icon btn-icon-danger icon-delete toolip" title="حذف"></a>
                                                        </td>
                                                    </tr>
                                                    @if(isset($category['list']))
                                                        @foreach ($category['list'] as $category)
                                                            <tr>
                                                                <td>{{ ++$i }}</td>
                                                                <td>{{ $category['title'] }}</td>
                                                                <td>{{ $category['record']->parentField() }}</td>
                                                                <td>{{ $category['record']->post_type }}</td>
                                                                <td><a target="_blankga" class="color-primary" href="{{ url("/admin/posts?filters=true&search=&categories[]={$category['id']}") }}">{{ $category['record']->posts->count() }}</a></td>
                                                                <td>
                                                                    <a class="btn-icon btn-icon-success icon-pencil toolip" title="ویرایش"
                                                                       href="{{ route('admin.categories.edit', ['id' => $category['id']]) }}"></a>
                                                                    <a href="{{ route('admin.categories.destroy.confirm', ['id' => $category['id']]) }}?url={{ url()->current() }}" class="btn-icon btn-icon-danger icon-delete toolip" title="حذف"></a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif

                        @endforeach

                        </tbody>
                    </table>
                </div>

                <div class="tac pt15">
                    {!! $categories->render() !!}
                </div>

            </div>
        </div>
    </div>

@endsection






