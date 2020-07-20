<div class="sidebar">

    <?php
        $user = auth()->user();
    ?>

    <ul class="main-ul">

        <li class="">
            <a class="sidebar-item" href="{{ route('admin.dashboard') }}">
                <i class="icon-meter"></i>
                <span>پیشخوان</span>
            </a>
        </li>

        @foreach (getPostTypes() as $postType)
            @if($user->checkPostTypePermission($postType->type, 'post_list', true))
                <li class="treeview">

                    <a class="sidebar-item">
                        <i class="{{ $postType->icon }}"></i>
                        <span>{{ $postType->total_label }}</span>
                        <i class="icon-toggle icon-keyboard_arrow_left"></i>
                    </a>

                    <ul class="submenu">

                        @if($user->checkPostTypePermission($postType->type, 'post_create', true))
                            <li class="">
                                <a class="sidebar-item" href="{{ url('admin/posts/create?type=' . $postType->type) }}">
                                    <i class="icon-add"></i>
                                    <span>افزودن {{ $postType->label }}</span>
                                </a>
                            </li>
                        @endif

                        <li class="">
                            <a class="sidebar-item" href="{{ url('admin/posts/?postTypes[]=' . $postType->type) }}">
                                <i class="{{ $postType->icon }}"></i>
                                <span>{{ $postType->total_label }}</span>
                            </a>
                        </li>

                        @if($user->checkPostTypePermission($postType->type, 'category_create', true))
                            <li class="">
                                <a class="sidebar-item" href="{{ url('admin/categories/create?postType=' . $postType->type) }}">
                                    <i class="icon-add"></i>
                                    <span>دسته جدید</span>
                                </a>
                            </li>
                        @endif

                        @if($user->checkPostTypePermission($postType->type, 'category_list', true))
                            <li class="">
                                <a class="sidebar-item" href="{{ url('admin/categories?postType=' . $postType->type) }}">
                                    <i class="icon-storage"></i>
                                    <span>دسته ها</span>
                                </a>
                            </li>
                        @endif

                    </ul>

                </li>
            @endif
        @endforeach

        @foreach (sidebar() as $tree1)

            @if(sidebarPermission($tree1))

                <li class="{{ sidebarTreeView($tree1) }}">

                    <a href="{{ sidebarHref($tree1) }}" class="sidebar-item">
                        <i class="{{ $tree1['icon'] }}"></i>
                        <span>{{ $tree1['title'] }}</span>
                        @if(isset($tree1['treeview']))
                            <i class="icon-toggle icon-keyboard_arrow_left"></i>
                        @endif
                    </a>

                    @if(isset($tree1['treeview']))
                        <ul class="submenu">
                            @foreach ($tree1['treeview'] as $tree2)
                                @if(sidebarPermission($tree2))

                                    <li class="{{ sidebarTreeView($tree2) }}">

                                        <a href="{{ sidebarHref($tree2) }}" class="sidebar-item">
                                            <i class="{{ $tree2['icon'] }}"></i>
                                            <span>{{ $tree2['title'] }}</span>
                                            @if(isset($tree2['treeview']))
                                                <i class="icon-toggle icon-keyboard_arrow_left"></i>
                                            @endif
                                        </a>

                                        @if(isset($tree2['treeview']))
                                            <ul class="submenu">
                                                @foreach ($tree2['treeview'] as $tree3)
                                                    @if(sidebarPermission($tree3))

                                                        <li class="{{ sidebarTreeView($tree3) }}">

                                                            <a href="{{ sidebarHref($tree3) }}" class="sidebar-item">
                                                                <i class="{{ $tree3['icon'] }}"></i>
                                                                <span>{{ $tree3['title'] }}</span>
                                                                @if(isset($tree3['treeview']))
                                                                    <i class="icon-toggle icon-keyboard_arrow_left"></i>
                                                                @endif
                                                            </a>

                                                        </li>

                                                    @endif
                                                @endforeach
                                            </ul>
                                        @endif

                                    </li>

                                @endif
                            @endforeach
                        </ul>
                    @endif

                </li>

            @endif

        @endforeach

        @if(isDev())
            <li class="treeview">

                <a class="sidebar-item">
                    <i class="icon-library_books"></i>
                    <span>پست تایپ ها</span>
                    <i class="icon-toggle icon-keyboard_arrow_left"></i>
                </a>

                <ul class="submenu">
                    <li class="">
                        <a class="sidebar-item" href="{{ route('admin.post-types.create') }}">
                            <i class="icon-add"></i>
                            <span>افزودن پست تایپ</span>
                        </a>
                    </li>
                    <li class="">
                        <a class="sidebar-item" href="{{ route('admin.post-types.index') }}">
                            <i class="icon-library_books"></i>
                            <span>پست تایپ ها</span>
                        </a>
                    </li>
                </ul>

            </li>
        @endif

        @if($user->can('tags'))
            <li class="treeview">

                <a class="sidebar-item">
                    <i class="icon-style"></i>
                    <span>برچسب ها</span>
                    <i class="icon-toggle icon-keyboard_arrow_left"></i>
                </a>

                <ul class="submenu">
                    @if($user->can('createTag'))
                        <li class="">
                            <a class="sidebar-item" href="{{ route('admin.tags.create') }}">
                                <i class="icon-add"></i>
                                <span>افزودن برچسب</span>
                            </a>
                        </li>
                    @endif
                    <li class="">
                        <a class="sidebar-item" href="{{ route('admin.tags.index') }}">
                            <i class="icon-style"></i>
                            <span>برچسب ها</span>
                        </a>
                    </li>
                </ul>

            </li>
        @endif

        @if($user->can('attributes'))
            <li class="treeview">

                <a class="sidebar-item">
                    <i class="icon-folder_special"></i>
                    <span>ویژگی ها</span>
                    <i class="icon-toggle icon-keyboard_arrow_left"></i>
                </a>

                <ul class="submenu">
                    @if($user->can('createAttribute'))
                        <li class="">
                            <a class="sidebar-item" href="{{ route('admin.attributes.create') }}">
                                <i class="icon-add"></i>
                                <span>افزودن ویژگی</span>
                            </a>
                        </li>
                    @endif
                    <li class="">
                        <a class="sidebar-item" href="{{ route('admin.attributes.index') }}">
                            <i class="icon-done_all"></i>
                            <span>ویژگی ها</span>
                        </a>
                    </li>
                    @if($user->can('createAttributeKey'))
                        <li class="">
                            <a class="sidebar-item" href="{{ route('admin.attribute-keys.create') }}">
                                <i class="icon-add"></i>
                                <span>افزودن کلید</span>
                            </a>
                        </li>
                    @endif
                    @if($user->can('listAttributeKey'))
                        <li class="">
                            <a class="sidebar-item" href="{{ route('admin.attribute-keys.index') }}">
                                <i class="icon-done_all"></i>
                                <span>کلید ها</span>
                            </a>
                        </li>
                    @endif
                    @if($user->can('createAttributeValue'))
                        <li class="">
                            <a class="sidebar-item" href="{{ route('admin.attribute-values.create') }}">
                                <i class="icon-add"></i>
                                <span>افزودن مقدار</span>
                            </a>
                        </li>
                    @endif
                    @if($user->can('listAttributeValue'))
                        <li class="">
                            <a class="sidebar-item" href="{{ route('admin.attribute-values.index') }}">
                                <i class="icon-done_all"></i>
                                <span>مقادیر</span>
                            </a>
                        </li>
                    @endif
                    @if($user->can('postSearchAttribute'))
                        <li class="">
                            <a class="sidebar-item" href="{{ route('admin.attributes.search') }}">
                                <i class="icon-search"></i>
                                <span>فیلتر جستجو</span>
                            </a>
                        </li>
                    @endif
                </ul>

            </li>
        @endif

        @if($user->can('menus'))
            <li class="treeview">

                <a class="sidebar-item">
                    <i class="icon-menu"></i>
                    <span>فهرست ها</span>
                    <i class="icon-toggle icon-keyboard_arrow_left"></i>
                </a>

                <ul class="submenu">
                    @if($user->can('createMenu'))
                        <li class="">
                            <a class="sidebar-item" href="{{ route('admin.menus.create') }}">
                                <i class="icon-add"></i>
                                <span>افزودن فهرست</span>
                            </a>
                        </li>
                    @endif
                    <li class="">
                        <a class="sidebar-item" href="{{ route('admin.menus.index') }}">
                            <i class="icon-done_all"></i>
                            <span>فهرست ها</span>
                        </a>
                    </li>
                </ul>

            </li>
        @endif

        @if($user->can('reports'))
            <li class="treeview">

                <a class="sidebar-item">
                    <i class="icon-stats-bars"></i>
                    <span>گزارشات</span>
                    <i class="icon-toggle icon-keyboard_arrow_left"></i>
                </a>

                <ul class="submenu">
                    @if($user->can('searchReport'))
                        <li class="">
                            <a class="sidebar-item" href="{{ route('admin.reports.search') }}">
                                <i class="icon-search"></i>
                                <span>گزارش جستجو</span>
                            </a>
                        </li>
                    @endif
                </ul>

            </li>
        @endif

        @if($user->can('comments'))
            <li>

                <a class="sidebar-item">
                    <i class="icon-bubbles4"></i>
                    <span>نظرات</span>
                    <i class="icon-toggle icon-keyboard_arrow_left"></i>
                </a>

                <ul class="submenu">
                    <li class="">
                        <a class="sidebar-item" href="{{ route('admin.comments.index') }}?type=1&status=1">
                            <i class="icon-close"></i>
                            <span>درانتظار تایید</span>
                        </a>
                    </li>
                    <li class="">
                        <a class="sidebar-item" href="{{ route('admin.comments.index') }}?status=2">
                            <i class="icon-check"></i>
                            <span>منتشر شده</span>
                        </a>
                    </li>
                </ul>

            </li>
        @endif

        @if($user->can('tickets'))
            <li>

                <a class="sidebar-item">
                    <i class="icon-lifebuoy"></i>
                    <span>تیکت ها</span>
                    <i class="icon-toggle icon-keyboard_arrow_left"></i>
                </a>

                <ul class="submenu">
                    <li class="">
                        <a class="sidebar-item" href="{{ route('admin.tickets.index') }}?status=1">
                            <i class="icon-close"></i>
                            <span>درانتظار پاسخ</span>
                        </a>
                    </li>
                    <li class="">
                        <a class="sidebar-item" href="{{ route('admin.tickets.index') }}?status=2">
                            <i class="icon-check"></i>
                            <span>پاسخ داده شده</span>
                        </a>
                    </li>
                    <li class="">
                        <a class="sidebar-item" href="{{ route('admin.tickets.index') }}?status=3">
                            <i class="icon-close"></i>
                            <span>بسته شده</span>
                        </a>
                    </li>
                </ul>

            </li>
        @endif

        @if($user->can('users'))
            <li class="treeview">

                <a class="sidebar-item">
                    <i class="icon-users"></i>
                    <span>کاربران</span>
                    <i class="icon-toggle icon-keyboard_arrow_left"></i>
                </a>

                <ul class="submenu">
                    @if($user->can('createUser'))
                        <li class="">
                            <a class="sidebar-item" href="{{ route('admin.users.create') }}">
                                <i class="icon-add"></i>
                                <span>افزودن کاربر</span>
                            </a>
                        </li>
                    @endif
                    <li class="">
                        <a class="sidebar-item" href="{{ route('admin.users.index') }}">
                            <i class="icon-done_all"></i>
                            <span>کاربران</span>
                        </a>
                    </li>
                    @if($user->can('permissions'))
                        <li class="">
                            <a class="sidebar-item" href="{{ route('admin.permissions.index') }}">
                                <i class="icon-card_membership"></i>
                                <span>مجوز ها</span>
                            </a>
                        </li>
                    @endif
                    @if($user->can('roles'))
                        <li class="">
                            <a class="sidebar-item" href="{{ route('admin.roles.index') }}">
                                <i class="icon-person_pin"></i>
                                <span>نقش ها</span>
                            </a>
                        </li>
                    @endif
                </ul>

            </li>
        @endif

        @if($user->can('settings'))
            <li class="treeview">

                <a class="sidebar-item">
                    <i class="icon-cog"></i>
                    <span>تنظیمات</span>
                    <i class="icon-toggle icon-keyboard_arrow_left"></i>
                </a>

                <ul class="submenu">
                    @if($user->can('generalSetting'))
                        <li class="">
                            <a class="sidebar-item" href="{{ route('admin.options.general') }}">
                                <i class="icon-earth"></i>
                                <span>تنظیمات عمومی</span>
                            </a>
                        </li>
                    @endif
                    @if($user->can('imagesSetting'))
                        <li class="">
                            <a class="sidebar-item" href="{{ route('admin.options.images') }}">
                                <i class="icon-image"></i>
                                <span>تنظیمات تصاویر</span>
                            </a>
                        </li>
                    @endif
                    @if($user->can('themeValuesSetting'))
                        <li class="">
                            <a class="sidebar-item" href="{{ route('admin.options.themeValues') }}">
                                <i class="icon-text-color"></i>
                                <span>تنظیمات قالب</span>
                            </a>
                        </li>
                    @endif
                    @if($user->can('protocolSetting'))
                        <li class="">
                            <a class="sidebar-item" href="{{ route('admin.options.protocol') }}">
                                <i class="icon-http"></i>
                                <span>تنظیمات پروتکل</span>
                            </a>
                        </li>
                    @endif
                    @if($user->can('languageSetting'))
                        <li class="">
                            <a class="sidebar-item" href="{{ route('admin.options.language') }}">
                                <i class="icon-language"></i>
                                <span>تنظیمات زبان</span>
                            </a>
                        </li>
                    @endif
                </ul>

            </li>
        @endif

    </ul>

</div>
