@php
if (!isset($user)) {
    $user = auth()->user();
}
@endphp
<input type="hidden" name="loading" value="{{ image('loading.png', 'admin') }}">
<div class="box box-widget widget-user">
    <div class="widget-user-header bg-aqua-active tar" style="background-image: url('{{ image('auth-box.jpg', 'admin') }}')">
        <h3 class="widget-user-username tac">{{ auth()->user()->name() }}</h3>
        <h5 class="widget-user-desc tal">{{ auth()->user()->roleName() }}</h5>
    </div>
    <div class="widget-user-image">
{{--                                                                        {{ route('updateProfileImage') }}--}}
        <form class="upload-profile-image-form" enctype="multipart/form-data" action="" method="post">
            @csrf
            <label for="upload-profile-image">
                <input id="upload-profile-image" type="file" name="file" class="dn upload-profile-image"/>
                <img class="profile-image img-circle" src="{{ $user->avatar() }}" alt="User Avatar">
                <span class="ic-edit icon-pencil"></span>
            </label>
        </form>
    </div>
    <div class="box-body mt30 tac">
{{--        "{{ route('profile') }}"--}}
        <a style="height: auto; line-height: normal" class="btn btn-primary" href=>پروفایل</a>
        <a style="height: auto; line-height: normal" class="btn btn-warning" href="">تغییر رمز عبور</a>
        <a style="height: auto; line-height: normal" class="btn btn-danger" href="{{ route('logout') }}">خروج</a>
        <h6 class="m0 mt10 font-weight-normal">آخرین بازدید : {{ auth()->user()->lastSeen() }} </h6>
    </div>
</div>
