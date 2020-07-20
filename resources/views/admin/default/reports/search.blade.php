@extends("admin.{$adminTheme}.master")
@section('title', 'گزارش جستجو')

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

                <form id="filters" action="" method="get">

                    <div class="row filters" style="background: #f1f1f1; border-radius: 5px; border: 1px solid #ddd; padding: 5px; margin-bottom: 5px;">

                        <div class="col-lg-3">
                            <div class="input-group mt10">
                                <label>جستجو</label>
                                <input style="background: white;" placeholder="" type="text" name="q" value="{{ $_GET['q'] ?? '' }}">
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="input-group mt10">
                                <label>گروه بندی بر اساس</label>
                                <select style="background: white;" name="groupBy">
                                    <option value="">هیچکدام</option>
                                    <option {{ selected($_GET['groupBy'] ?? '', 'keyword') }} value="keyword">کلمه</option>
                                    <option {{ selected($_GET['groupBy'] ?? '', 'count') }} value="count">پست</option>
                                    <option {{ selected($_GET['groupBy'] ?? '', 'created_at') }} value="created_at">تاریخ</option>
                                    <option {{ selected($_GET['groupBy'] ?? '', 'check') }} value="check">بررسی</option>
                                    <option {{ selected($_GET['groupBy'] ?? '', 'os') }} value="os">os</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-1">
                            <div class="input-group mt10">
                                <label>os</label>
                                <select style="background: white;" name="os">
                                    <option value="">همه</option>
                                    <option {{ selected($_GET['os'] ?? '', 'desktop') }} value="desktop">desktop</option>
                                    <option {{ selected($_GET['os'] ?? '', 'mobile') }} value="mobile">mobile</option>
                                    <option {{ selected($_GET['os'] ?? '', 'android') }} value="android">android</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="input-group mt10">
                                <label>بررسی</label>
                                <select style="background: white;" name="check">
                                    <option value="">همه</option>
                                    <option {{ selected($_GET['check'] ?? '', 'ok') }} value="ok">شده</option>
                                    <option {{ selected($_GET['check'] ?? '', 'nok') }} value="nok">نشده</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="input-group mt10">
                                <label>مرتب سازی بر اساس</label>
                                <select style="background: white;" name="orderBy">
                                    <option {{ selected($_GET['orderBy'] ?? '', 'id') }} value="id">آیدی</option>
                                    <option {{ selected($_GET['orderBy'] ?? '', 'keyword') }} value="keyword">کلمه</option>
                                    <option {{ selected($_GET['orderBy'] ?? '', 'user_id') }} value="user_id">کاربر</option>
                                    <option {{ selected($_GET['orderBy'] ?? '', 'count') }} value="count">نتایج</option>
                                    <option {{ selected($_GET['orderBy'] ?? '', 'created_at') }} value="created_at">تاریخ</option>
                                    <option {{ selected($_GET['orderBy'] ?? '', 'check') }} value="check">بررسی</option>
                                    <option {{ selected($_GET['orderBy'] ?? '', 'os') }} value="os">os</option>
                                    <option {{ selected($_GET['orderBy'] ?? '', 'total') }} value="total">تعداد</option>
                                </select>
                            </div>
                            <input type="hidden" name="ordering" value="desc">
                        </div>

                        <div class="col-lg-2">
                            <div class="input-group mt10">
                                <label>چینش</label>
                                <select style="background: white;" name="ordering">
                                    <option {{ selected($_GET['ordering'] ?? '', 'desc') }} value="desc">نزولی</option>
                                    <option {{ selected($_GET['ordering'] ?? '', 'asc') }} value="asc">صعودی</option>
                                </select>
                            </div>
                        </div>

                        <script>
                            $(document).on('change', '#filters select', function () {
                                $('#filters').submit();
                            });
                        </script>

                    </div>

                </form>

                <div class="dragscroll responsive-table">
                    <table class="tac">
                        <thead>
                        <tr>
                            <th></th>
                            <th>کاربر</th>
                            <th>os</th>
                            <th>کلمه</th>
                            <th>نتایج</th>
                            <th>ip</th>
                            <th>تاریخ</th>
                            <th>بررسی</th>
                            @if(!empty($_GET['groupBy'] ?? ''))
                            <th>تعداد</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>

                        @php
                            $i = $records->perPage() * ($records->currentPage() - 1);
                        @endphp

                        @foreach($records as $record)
                            <?php
                                $osIcon = 'icon-laptop';
                                if ($record->os == 'mobile') {
                                    $osIcon = 'icon-mobile';
                                } else if ($record->os == 'android') {
                                    $osIcon = 'icon-android';
                                }
                            ?>
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{!! $record->user_id == null ? '-' : '<a target="_blank" href="'.url("admin/users/{$record->user_id}/edit").'">' . \LaraBase\Auth\Models\User::find($record->user_id)->name() . '</a>' !!}</td>
                                <td><i title="{{ $record->os }}" class="{{ $osIcon }}"></i></td>
                                <td><a target="_blank" href="{{ $record->url }}&noLog=true">{{ $record->keyword }}</a></td>
                                <td>{{ $record->count }}</td>
                                <td>{{ $record->ip }}</td>
                                <td>{{ jDateTime('H:i Y/m/d', strtotime($record->created_at)) }}</td>
                                <td style="cursor: pointer;"><a href="{{ url('admin/reports/search/check/' . $record->keyword . '/' . ($record->check ? '0' : '1')) }}">{!! $record->check ? '<span style="color: green;">شده</span>' : '<span style="color: red;">نشده</span>' !!}</a></td>
                                @if(!empty($_GET['groupBy'] ?? ''))
                                    <th>{{ $record->total ?? '-' }}</th>
                                @endif
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>

                <div class="tac pt15">
                    {!! $records->appends(request()->query())->links() !!}
                </div>

            </div>

        </div>

    </div>

@endsection
