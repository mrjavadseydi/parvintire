@extends("admin.{$adminTheme}.master")
@section('title', 'ویرایش مجوز')

@section('content')

    <div class="col-lg-4">

        <form action="{{ route('admin.users.permissions.update', ['id' => $permission->id]) }}" method="post" class="box-solid box-primary">

            <div class="box-header">
                <h3 class="box-title">@yield('title')</h3>
                <div class="box-tools">
                    <i class="box-tools-icon icon-minus"></i>
                </div>
            </div>

            <div class="box-body">

                @csrf
                @method('patch')

                <div class="input-group">
                    <i class="icon-title"></i>
                    <label>عنوان</label>
                    <input autocomplete="off" type="text" name="label" value="{{ old('label') ?? $permission->label }}">
                </div>

                <div class="input-group">
                    <i class="icon-card_membership"></i>
                    <label>مجوز <small>(فرمت مجاز : کاراکترهای انگلیسی)</small></label>
                    <input autocomplete="off" type="text" name="name" value="{{ old('name') ?? $permission->name }}">
                </div>

            </div>

            <div class="box-footer tal">
                <button type="submit" class="btn-lg btn-success">ذخیره</button>
            </div>

        </form>

    </div>

@endsection
