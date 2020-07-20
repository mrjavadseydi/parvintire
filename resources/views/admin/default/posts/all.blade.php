@extends("admin.{$adminTheme}.master")
@section('title', ($postType == 'all' ? 'همه مطالب' : $post->postTypeTotalLabel()))

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

                @include('admin.default.languages', ['action' => url('admin/posts/translate')])

                <form action="" method="get">

                    <input type="hidden" name="filters" value="true">
                    <div class="row filters">
                        <div class="col-lg-4">
                            @include('filters.default.search')
                        </div>

                        <div class="col-lg-4 pt10">
                            @include('boxes.default.users', [
                                'box' => [
                                    'title' => 'نویسنده',
                                    'url' => 'api/users/search',
                                    'dir' => 'rtl',
                                    'maximumSelectionLength' => 50,
                                    'tags' => false
                                ],
                                'key' => 'users',
                            ])
                        </div>

                        <div class="col-lg-4">
                            @include('filters.default.postType')
                        </div>

                        <div class="col-lg-12">
                            <div class="row">
                                @include('filters.default.world')
                            </div>
                        </div>

                        <div class="col-lg-4">
                            @include('filters.default.category')
                        </div>

                        <div class="col-lg-4">
                            @include('filters.default.postStatus')
                        </div>

                        <div class="col-lg-4">
                            @include('filters.default.postFinalStatus')
                        </div>

                        <div class="col-12 mt5 tal">
                            <button class="btn-lg btn-success">اعمال فیلتر</button>
                        </div>

                        @include('filters.default.script')

                    </div>

                </form>

                <div class="dragscroll responsive-table">
                    <table class="tac">
                        <thead>
                        <tr>
                            <th></th>
                            <th>عنوان</th>
                            <th>نویسنده</th>
                            <th>دسته</th>
                            <th>بازدید</th>
                            <th>نوع</th>
                            <th>تاریخ</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>

                        @php
                            $i = $posts->perPage() * ($posts->currentPage() - 1);
                        @endphp

                        @foreach($posts as $post)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td><a class="color-primary" target="_blank" href="{{ $post->href() }}">{{ $post->title }}</a></td>

                                <td><a class="color-black" href="{{ url("admin/posts?search=&users[]={$post->user_id}&filters=true") }}">{{ $post->user()->name() }}</a></td>
                                <td>{{ $post->categories->implode('title', ', ') }}</td>
                                <td>{{ getPostVisit($post->id) }}</td>
                                <td class="tooltip" title="{{ (!empty($post->post_type) ? $post->postTypeLabel() : '-') }}"><i class="{{ (!empty($post->post_type) ? $post->postTypeLabel() : '-') }}"></i> {{ $post->postTypeLabel() }}</td>
                                <td class="ltr">{{ $post->createdAt('date') }}</td>
                                <td style="color: {{ $post->statusColor() }}; background-color: {{ $post->statusLightColor() }};">{{ $post->status() }}</td>
                                <td style="width: 150px">
                                    <a target="_blank" class="mb2 btn-icon btn-icon-success icon-pencil toolip" title="ویرایش"
                                       href="{{ route('admin.posts.edit', ['id' => $post->id]) }}"></a>
                                    <a target="_blank" class="mb2 btn-icon btn-icon-info icon-visibility toolip" title="مشاهده"
                                       href="{{ $post->href() }}"></a>
                                    @php
                                    if (!empty($post->final_status)) {
                                        $finalStatus = config("status.postStatus.{$post->final_status}");
                                    }
                                    @endphp
                                    @can('finalStatus')
                                        <a style="background: {{ $post->finalStatusColor() }}; border-color: {{ $post->finalStatusColor() }}" class="mb2 btn-icon icon-verified_user toolip" title="{{ $post->finalStatus() }}"></a>
                                    @endcan
                                    {{ delete([
                                       'title' => $post->title,
                                       'icon' => 'btn-icon btn-icon-danger icon-delete tooltip',
                                       'action' => route('admin.posts.destroy', $post)
                                    ]) }}
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>

                <div class="row pt15">
                    <div class="col-lg-6">
                        <label>{{ $posts->total()  }} نتیجه یافت شد | <b class="color-pink">{{ $posts->currentPage() }}</b> از {{ $posts->lastPage() }}</label>
                    </div>
                    <div class="col-lg-6 tal">
                        {!! $posts->render() !!}
                    </div>
                </div>

            </div>

        </div>

    </div>

@endsection

@section('head-content')
    <link rel="stylesheet" href="/plugins/select2/select2.css">
    <script src="/plugins/select2/select2.min.js"></script>
@endsection
