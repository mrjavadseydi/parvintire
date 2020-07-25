<sidebar class="scrollbar-1">

    <?php
    $user = auth()->user();
    ?>

    <ul>

        <li class="">
            <a class="sidebar-item" href="{{ route('admin.dashboard') }}">
                <i class="fad fa-tachometer"></i>
                <span>پیشخوان</span>
            </a>
        </li>

        @foreach (getPostTypes() as $postType)
            @if($user->checkPostTypePermission($postType->type, 'post_list', true))
                <li class="treeview">

                    <a class="sidebar-item">
                        <i class="{{ $postType->icon }}"></i>
                        <span>{{ $postType->total_label }}</span>
                        <i class="arrow fad fa-angle-left"></i>
                    </a>

                    <ul class="treeview-ul">

                        @if($user->checkPostTypePermission($postType->type, 'post_create', true))
                            <li>
                                <a href="{{ url('admin/posts/create?type=' . $postType->type) }}">
                                    <i class="icon-add"></i>
                                    <span>افزودن {{ $postType->label }}</span>
                                </a>
                            </li>
                        @endif

                        <li>
                            <a href="{{ url('admin/posts/?postTypes[]=' . $postType->type) }}">
                                <i class="{{ $postType->icon }}"></i>
                                <span>{{ $postType->total_label }}</span>
                            </a>
                        </li>

                        @if($user->checkPostTypePermission($postType->type, 'category_create', true))
                            <li>
                                <a href="{{ url('admin/categories/create?postType=' . $postType->type) }}">
                                    <i class="icon-add"></i>
                                    <span>دسته جدید</span>
                                </a>
                            </li>
                        @endif

                        @if($user->checkPostTypePermission($postType->type, 'category_list', true))
                            <li>
                                <a href="{{ url('admin/categories?postType=' . $postType->type) }}">
                                    <i class="icon-storage"></i>
                                    <span>دسته ها</span>
                                </a>
                            </li>
                        @endif

                        @if($user->checkPostTypePermission($postType->type, 'comment_list', true))
                            <li>
                                <a href="{{ url('admin/comments?postType=' . $postType->type) }}">
                                    <i class="fad fa-comments"></i>
                                    <span>نظرات</span>
                                </a>
                            </li>
                        @endif

                    </ul>

                </li>
            @endif
        @endforeach

        @foreach(sidebar() as $sidebar)
            @if(sidebarPermission($sidebar['permission'] ?? ''))
                <li class="{{ (isset($sidebar['treeview']) ? 'treeview' : '') }}">
                    <a {{ isset($sidebar['href']) ? "href={$sidebar['href']}" : '' }}>
                        <i class="{{ $sidebar['icon'] }}"></i>
                        <span>{{ $sidebar['title'] }}</span>
                        <i class="arrow fad fa-angle-left"></i>
                    </a>
                    @if(isset($sidebar['treeview']))
                        <ul class="treeview-ul">
                            @foreach($sidebar['treeview'] as $treeview)
                                @if(sidebarPermission($treeview['permission'] ?? ''))
                                    <li>
                                        <a {{ isset($treeview['href']) ? "href={$treeview['href']}" : '' }}>
                                            <i class="{{ $treeview['icon'] }}"></i>
                                            <span>{{ $treeview['title'] }}</span>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endif
        @endforeach

        <li class="treeview">

            <a class="sidebar-item">
                <i class="fad fa-tools"></i>
                <span>ابزار</span>
                <i class="arrow fad fa-angle-left"></i>
            </a>

            <ul class="treeview-ul">

                @if(isDev())
                    @if($user->can('postTypes'))
                        <li class="treeview">
                            <a href="{{ route('admin.post-types.index') }}" class="sidebar-item">
                                <i class="fad fa-shredder"></i>
                                <span>پست تایپ ها</span>
                            </a>
                        </li>
                    @endif
                @endif

                @if($user->can('tags'))
                    <li class="treeview">
                        <a href="{{ route('admin.tags.index') }}" class="sidebar-item">
                            <i class="fad fa-tags"></i>
                            <span>برچسب ها</span>
                        </a>
                    </li>
                @endif

                @if($user->can('attributes'))
                    <li class="treeview">
                        <a href="{{ route('admin.attributes.index') }}" class="sidebar-item">
                            <i class="fad fa-award"></i>
                            <span>ویژگی ها</span>
                        </a>
                    </li>
                @endif

                @if($user->can('menus'))
                    <li class="treeview">
                        <a href="{{ route('admin.menus.index') }}" class="sidebar-item">
                            <i class="fad fa-bars"></i>
                            <span>فهرست ها</span>
                        </a>
                    </li>
                @endif

            </ul>

        </li>

        @if($user->can('tickets'))
            <li class="treeview">

                <a class="sidebar-item">
                    <i class="fad fa-user-headset"></i>
                    <span>تیکت ها</span>
                    <i class="arrow fad fa-angle-left"></i>
                </a>

                <ul class="treeview-ul">
                    @foreach (config('comment.status.ticket') as $status => $values)
                        <li>
                            <a href="#">
                                <i class="{{ $values['icon'] }}"></i>
                                <span>{{ $values['title'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>

            </li>
        @endif

        @if($user->can('users'))
            <li class="treeview">

                <a class="sidebar-item">
                    <i class="fad fa-users"></i>
                    <span>کاربران</span>
                    <i class="arrow fad fa-angle-left"></i>
                </a>

                <ul class="treeview-ul">
                    @if($user->can('createUser'))
                        <li>
                            <a href="{{ route('admin.users.create') }}">
                                <i class="fad fa-plus"></i>
                                <span>افزودن کاربر</span>
                            </a>
                        </li>
                    @endif
                    <li>
                        <a href="{{ route('admin.users.index') }}">
                            <i class="fad fa-users"></i>
                            <span>کاربران</span>
                        </a>
                    </li>
                    @if($user->can('permissions'))
                        <li>
                            <a href="{{ route('admin.permissions.index') }}">
                                <i class="fad fa-badge-check"></i>
                                <span>مجوز ها</span>
                            </a>
                        </li>
                    @endif
                    @if($user->can('roles'))
                        <li>
                            <a href="{{ route('admin.roles.index') }}">
                                <i class="fad fa-user-tie"></i>
                                <span>نقش ها</span>
                            </a>
                        </li>
                    @endif
                </ul>

            </li>
        @endif

        @if($user->can('configuration'))
            <li class="treeview">

                <a class="sidebar-item">
                    <i class="fad fa-box-check"></i>
                    <span>پیکربندی</span>
                    <i class="arrow fad fa-angle-left"></i>
                </a>

                <ul class="treeview-ul">
                    @if($user->can('updateCore'))
                        <li>
                            <a href="{{ route('larabase.update.install') }}">
                                <i class="fad fa-sync-alt"></i>
                                <span>بروزرسانی پروژه</span>
                            </a>
                        </li>
                    @endif
                    @if($user->can('updateDatabase'))
                        <li>
                            <a href="{{ route('larabase.migrate') }}">
                                <i class="fad fa-database"></i>
                                <span>همگام سازی پایگاه</span>
                            </a>
                        </li>
                    @endif
                    @if($user->can('updateThemes'))
                        <li>
                            <a href="{{ route('larabase.update.themes') }}">
                                <i class="fad fa-palette"></i>
                                <span>بروز رسانی قالب‌ها</span>
                            </a>
                        </li>
                    @endif
                    @if($user->can('initProject'))
                        <li>
                            <a href="{{ route('larabase.init') }}">
                                <i class="fad fa-cubes"></i>
                                <span>پیکربندی پروژه</span>
                            </a>
                        </li>
                    @endif
                </ul>

            </li>
        @endif

        @if($user->can('settings'))
            <li class="treeview">

                <a class="sidebar-item">
                    <i class="fad fa-cog"></i>
                    <span>تنظیمات</span>
                    <i class="arrow fad fa-angle-left"></i>
                </a>

                <ul class="treeview-ul">
                    @if($user->can('generalSetting'))
                        <li>
                            <a href="{{ route('admin.options.general') }}">
                                <i class="fad fa-browser"></i>
                                <span>عمومی</span>
                            </a>
                        </li>
                    @endif
                    @if($user->can('imagesSetting'))
                        <li>
                            <a href="{{ route('admin.options.images') }}">
                                <i class="fad fa-images"></i>
                                <span>تصاویر</span>
                            </a>
                        </li>
                    @endif
                </ul>

            </li>
        @endif

    </ul>

</sidebar>

<script>

    $(document).on('click', '.menu', function () {
        if ($('sidebar').hasClass('active')) {
            $('sidebar').removeClass('active');
            $('.treeview-ul').slideUp();
            $('.treeview').removeClass('active').removeClass('clicked');
        } else {
            $('sidebar').addClass('active');
        }
        $(this).toggleClass('active');
    });

    $( "sidebar" ).hover(
        function() {
            $(this).addClass('open');
        }, function() {
            $(this).removeClass('open');
            if (!$(this).hasClass('active')) {
                $('.treeview-ul').slideUp();
                $('.treeview').removeClass('active').removeClass('clicked');
            }
        }
    );

    $(document).on('click', '.treeview a', function () {

        $('.treeview').removeClass('active');
        $('.treeview-ul').slideUp();

        if (!$(this).parent().hasClass('clicked')) {
            $(this).parent().toggleClass('active').addClass('clicked');
            $(this).parent().find('.treeview-ul').slideToggle();
        } else {
            $(this).parent().removeClass('clicked');
        }

    });

</script>
