@extends("admin.{$adminTheme}.master")
@section('title', 'ویرایش فهرست')

@section('content')

    <div class="row">

        <div class="col-md-4">
            <form action="{{ route('admin.menus.update', $menu) }}" method="post" class="box-solid box-info">

                @csrf
                @method('patch')

                <div class="box-header">
                    <h3 class="box-title">ویرایش فهرست</h3>
                    <div class="box-tools">
                        <i class="box-tools-icon icon-minus"></i>
                    </div>
                </div>

                <div class="box-body">

                    <div class="input-group">
                        <label for="">عنوان</label>
                        <input type="text" name="title" value="{{ old('title') ?? $menu->title }}">
                    </div>

                    <div class="input-group">
                        <label style="margin-bottom: 4px;display: block;">جایگاه ها</label>
                        <select class="places w100" name="places[]" multiple>
                            @foreach ($places as $key => $values)
                                <optgroup label="{{ ($key == 'admin' ? 'قالب مدیریت' : 'قالب سایت') }}">
                                    @foreach ($values as $place => $item)
                                        <option {{ in_array($place, $activePlaces) ? 'selected' : '' }} value="{{ $place }}">{{ $item['title'] }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="box-footer tal">
                    <button class="btn-lg btn-success">ذخیره</button>
                </div>

            </form>
        </div>

        <div class="col-md-8">

            <div class="box-solid box-success">

                <div class="box-header">
                    <h3 class="box-title">منو ها</h3>
                    <div class="box-tools">
                        <i class="box-tools-icon icon-minus"></i>
                    </div>
                </div>

                <div class="box-body">

                    <div class="input-group">
                        <a class="btn-lg btn-info" href="{{ route('admin.menu-items.create') }}?menu_id={{ $menu->id }}">افزودن منو</a>
                    </div>

                    <ul class="menus">

                        @foreach ($menuItems as $menuItem)
                            <li class="active-{{ $menuItem['active'] }}">
                                <a href="{{ route('admin.menu-items.edit', ['id' => $menuItem['id']]) }}">
                                    <span>{{ $menuItem['title'] }}</span>
                                </a>
                                <form method="get" action="{{ route('admin.menu-items.edit', ['id' => $menuItem['id']]) }}">
                                    <button class="edit-menu icon-mode_edit"></button>
                                </form>
                                <form method="get" action="{{ route('admin.menu-items.destroy.confirm', ['id' => $menuItem['id']]) }}">
                                    <input type="hidden" name="url" value="{{ url()->current() }}">
                                    <button class="delete-menu icon-delete"></button>
                                </form>
                                @if(isset($menuItem['list']))
                                    <ul class="menus treeview">
                                        @foreach ($menuItem['list'] as $menuItem2)
                                            <li class="active-{{ $menuItem2['active'] }}">
                                                <a href="{{ route('admin.menu-items.edit', ['id' => $menuItem2['id']]) }}">
                                                    <span>{{ $menuItem2['title'] }}</span>
                                                </a>
                                                <form method="get" action="{{ route('admin.menu-items.edit', ['id' => $menuItem2['id']]) }}">
                                                    <button class="edit-menu icon-mode_edit"></button>
                                                </form>
                                                <form method="get" action="{{ route('admin.menu-items.destroy.confirm', ['id' => $menuItem2['id']]) }}?url={{ url()->current() }}">
                                                    <input type="hidden" name="url" value="{{ url()->current() }}">
                                                    <button class="delete-menu icon-delete"></button>
                                                </form>
                                                @if(isset($menuItem2['list']))
                                                    <ul class="menus treeview">
                                                        @foreach ($menuItem2['list'] as $menuItem3)
                                                            <li class="active-{{ $menuItem3['active'] }}">
                                                                <a href="{{ route('admin.menu-items.edit', ['id' => $menuItem3['id']]) }}">
                                                                    <span>{{ $menuItem3['title'] }}</span>
                                                                </a>
                                                                <form method="get" action="{{ route('admin.menu-items.edit', ['id' => $menuItem3['id']]) }}">
                                                                    <button class="edit-menu icon-mode_edit"></button>
                                                                </form>
                                                                <form method="get" action="{{ route('admin.menu-items.destroy.confirm', ['id' => $menuItem3['id']]) }}?url={{ url()->current() }}">
                                                                    <input type="hidden" name="url" value="{{ url()->current() }}">
                                                                    <button class="delete-menu icon-delete"></button>
                                                                </form>
                                                                @if(isset($menuItem3['list']))
                                                                    <ul class="menus treeview">
                                                                        @foreach ($menuItem3['list'] as $menuItem4)
                                                                            <li class="active-{{ $menuItem4['active'] }}">
                                                                                <a href="{{ route('admin.menu-items.edit', ['id' => $menuItem4['id']]) }}">
                                                                                    <span>{{ $menuItem4['title'] }}</span>
                                                                                </a>
                                                                                <form method="get" action="{{ route('admin.menu-items.edit', ['id' => $menuItem4['id']]) }}">
                                                                                    <button class="edit-menu icon-mode_edit"></button>
                                                                                </form>
                                                                                <form method="get" action="{{ route('admin.menu-items.destroy.confirm', ['id' => $menuItem4['id']]) }}?url={{ url()->current() }}">
                                                                                    <input type="hidden" name="url" value="{{ url()->current() }}">
                                                                                    <button class="delete-menu icon-delete"></button>
                                                                                </form>
                                                                                @if(isset($menuItem4['list']))
                                                                                    <ul class="menus treeview">
                                                                                        @foreach ($menuItem4['list'] as $menuItem5)
                                                                                            <li class="active-{{ $menuItem5['active'] }}">
                                                                                                <a href="{{ route('admin.menu-items.edit', ['id' => $menuItem5['id']]) }}">
                                                                                                    <span>{{ $menuItem5['title'] }}</span>
                                                                                                </a>
                                                                                                <form method="get" action="{{ route('admin.menu-items.edit', ['id' => $menuItem5['id']]) }}">
                                                                                                    <button class="edit-menu icon-mode_edit"></button>
                                                                                                </form>
                                                                                                <form method="get" action="{{ route('admin.menu-items.destroy.confirm', ['id' => $menuItem5['id']]) }}?url={{ url()->current() }}">
                                                                                                    <input type="hidden" name="url" value="{{ url()->current() }}">
                                                                                                    <button class="delete-menu icon-delete"></button>
                                                                                                </form>
                                                                                            </li>
                                                                                        @endforeach
                                                                                    </ul>
                                                                                @endif
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach

                            <style>
                                .active-0 {
                                    opacity: 0.5;
                                }
                            </style>

                    </ul>

                    <style>

                        .menus {
                            padding: 0;
                        }

                        .menus li {
                            display: block;
                            position: relative;
                        }

                        .menus a {
                            position: relative;
                            color: #6f6d6d;
                            height: 40px;
                            line-height: 40px;
                            display: inline-block;
                            padding: 0 10px;
                            margin-bottom: 5px;
                            border-radius: 5px;
                            width: 400px;
                            background: #f1f1f1;
                            border: 1px solid #ddd;
                        }

                        .menus.treeview {
                            padding-right: 50px;
                        }

                        .menus form {
                            display: inline-block;
                            vertical-align: top;
                            position: absolute;
                        }

                        .menus .edit-menu, .menus .delete-menu {
                            border: none;
                            user-select: none;
                            position: absolute;
                            display: inline-block;
                            width: 40px;
                            height: 40px;
                            text-align: center;
                            line-height: 40px;
                            padding: 0;
                            border-radius: 3px;
                            color: white;
                            font-size: 1.2em;
                            background: #f1f1f1;
                            border: 1px solid #ddd;
                            cursor: pointer;
                        }

                        .menus .edit-menu {
                            left: -45px;
                            color: #1c7d1c;
                        }

                        .menus .delete-menu {
                            left: -90px;
                            color: #dc0000;
                        }

                        .menus .edit-menu:hover {
                            background-color: #d3f5d4;
                        }

                        .menus .delete-menu:hover {
                            background-color: #ffdddd;
                        }

                    </style>

                </div>

            </div>

        </div>

    </div>


    <script>

        $('.places').select2({
            dir: "rtl",
            tags: true
        });

    </script>

@endsection
