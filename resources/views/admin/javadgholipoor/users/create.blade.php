@extends("admin.{$adminTheme}.master")
@section('title', 'افزودن کاربر جدید')

@section('buttons')
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary"><i class="fad fa-users align-middle ml-2"></i>کاربران</a>
@endsection

@section('content')

    <form action="{{ route('admin.users.store') }}" method="post" class="card">

        <div class="card-body">

            @csrf

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">ایمیل / موبایل</label>
                <div class="col-sm-4">
                    <input type="text" name="userLogin" value="{{ old('userLogin') }}" class="form-control ltr text-left">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">رمزعبور</label>
                <div class="col-sm-4">
                    <input type="text" name="password" value="{{ generateInt($passwordLength) }}" class="form-control ltr text-left">
                </div>
            </div>

        </div>

        <div class="card-footer">
            <button class="btn btn-success">ذخیره</button>
        </div>

    </form>

@endsection
