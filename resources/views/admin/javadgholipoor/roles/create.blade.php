@extends("admin.{$adminTheme}.master")
@section('buttons')
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary"><i class="fad fa-users align-middle ml-2"></i>کاربران</a>
    <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-primary"><i class="fad fa-badge-check align-middle ml-2"></i>مجوز ها</a>
    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-primary"><i class="fad fa-user-tie align-middle ml-2"></i>نقش ها</a>
@endsection
@section('content')

    <form action="{{ route('admin.roles.store') }}" method="post" class="card">

        <div class="card-body">

            @csrf

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">عنوان</label>
                <div class="col-sm-9">
                    <input autocomplete="off" type="text" name="label" value="{{ old('label') }}" class="form-control">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-3 col-form-label">نقش <small>(فرمت مجاز : کاراکترهای انگلیسی)</small></label>
                <div class="col-sm-9">
                    <input autocomplete="off" type="text" name="name" value="{{ old('name') }}" class="form-control">
                </div>
            </div>

        </div>

        <div class="card-footer">
            <button type="submit" class="btn-lg btn-success">ذخیره</button>
        </div>

    </form>


@endsection
