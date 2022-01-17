@extends("admin.{$adminTheme}.master")
@section('title', 'افزودن کاربر جدید')

@section('content')

    <div class="row">
        <div class="col-lg-4 col-12">
            <form action="{{ route('admin.users.store') }}" method="post" class="box-solid box-info">

                @csrf
                <div class="box-header">
                    <h3 class="box-title">افزودن کاربر جدید</h3>
                    <div class="box-tools">
                        <i class="box-tools-icon icon-minus"></i>
                    </div>
                </div>

                <div class="box-body">

                    <div class="input-group">
                        <label for="">ایمیل / موبایل</label>
                        <input type="text" name="userLogin" value="{{ old('userLogin') }}">
                    </div>

                    <div class="input-group">
                        <label for="">رمزعبور</label>
                        <input type="text" name="password" value="{{ generateInt($passwordLength) }}">
                    </div>

                </div>

                <div class="box-footer tal">
                    <button class="btn-lg btn-success">ذخیره</button>
                </div>

            </form>
        </div>
    </div>

@endsection
