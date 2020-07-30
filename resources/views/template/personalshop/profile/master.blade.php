@extends(includeTemplate('master'))
@section('content')
    {!! uploader()->relation('profile', $user->id)->validations([
        'profile' => [
            'in' => 1,
            'key' => 'profile',
            'validations' => 'mimes:png,jpg,jpeg,gif,PNG,JPG,JPEG,GIF|min:0|max:2048',
            'method' => 'profile'
        ]
    ])->load() !!}
    <div class="container-fluid px-6 profile">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item"><a href="{{ url('profile') }}">پروفایل</a></li>
                @yield('breadcrumb')
            </ol>
        </nav>
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="shadow sidebar bg-white rounded p-3">
                    <div class="avatar text-center">
                        <figure onComplete="onComplete" onAddFiles="onAddFiles" url="{{ route('upload') }}" key="profile" class="uploader-click d-inline-block position-relative text-center">
                            <img id="avatar" class="rounded-circle shadow" src="{{ $user->avatar() }}" alt="{{ $user->name() }}">
                            <i class="change-avatar fa fa-edit"></i>
                        </figure>
                        <h6 class="text-center mt-3">{{ $user->name() }}</h6>
                        <small>{{ $user->mobile ?? $user->email }}</small>
                    </div>
                    <script>
                        function onAddFiles(response) {
                            $('#avatar').attr('src', '{{ image('loading.png', 'admin') }}')
                        }
                        function onComplete(status, response) {
                            if(status == 'success') {
                                $('#avatar').attr('src', response.thumbnail);
                            }
                        }
                    </script>
                    <ul>
                        <li class="active">
                            <a href="{{ route('profile') }}">
                                <i class="far fa-user align-middle"></i>
                                <span>پروفایل من</span>
                            </a>
                        </li>
                        <li class="active">
                            <a href="{{ route('profile.password') }}">
                                <i class="far fa-lock align-middle"></i>
                                <span>تغییر رمز عبور</span>
                            </a>
                        </li>
                        <li class="active">
                            <a href="{{ route('profile.orders') }}">
                                <i class="far fa-shopping-basket align-middle"></i>
                                <span>سفارشات</span>
                            </a>
                        </li>
                        <li class="active">
                            <a href="{{ route('profile.favorites') }}">
                                <i class="far fa-heart align-middle"></i>
                                <span>علاقه مندی ها</span>
                            </a>
                        </li>
                        <li class="active">
                            <a href="{{ route('logout') }}">
                                <i class="far fa-sign-out align-middle"></i>
                                <span>خروج از حساب</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-9 pr-0 pr-md-1">
                <div class="shadow content bg-white rounded p-3">
                    <div class="divider-2 d-flex justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-diamond align-middle"></i>
                            <h4>@yield('title')</h4>
                        </div>
                    </div>
                    <br>
                    @yield('main')
                </div>
            </div>
        </div>
    </div>
@endsection
