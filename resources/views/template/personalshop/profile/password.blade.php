@extends(includeTemplate('profile.master'))
@section('title', 'تغییر رمز عبور')
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">تغییر رمز عبور</li>
@endsection
@section('main')
    <form clear="#c1, #c2" action="{{ route('profile.password.update') }}" method="post" class="ajaxForm ajaxForm-iziToast">
        @csrf
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="pb-2" for="name">رمز عبور جدید</label>
                    <input id="c1" type="text" class="ltr text-left form-control" name="password" value="">
                </div>
                <div class="form-group">
                    <label class="pb-2" for="name">تکرار رمز عبور</label>
                    <input id="c2" type="text" class="ltr text-left form-control" name="password_confirmation" value="">
                </div>
                <button class="btn btn-block btn-outline-success mt-3">تغییر رمز عبور</button>
            </div>
        </div>
    </form>
@endsection
