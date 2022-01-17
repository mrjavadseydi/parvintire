@extends("admin.{$adminTheme}.master")
@section('title', 'ویرایش ' . $user->name())

@section('content')

    <div class="row">

        <div class="col-3">
            <input type="hidden" name="loading" value="{{ asset('/default/images/loading.png') }}">
            <div class="box box-widget widget-user">
                <div class="widget-user-header bg-aqua-active tar" style="background-image: url('{{ asset('/default/images/auth-box.jpg') }}')">
                    <h3 class="widget-user-username tac">{{ $user->name() }}</h3>
                    <h5 class="widget-user-desc tal">{{ $user->roleName() }}</h5>
                </div>
                <div class="widget-user-image">
                    <form class="upload-profile-image-form-2" enctype="multipart/form-data" action="{{ route('updateAvatar') }}" method="post">
                        @csrf
                        <label for="upload-profile-image-2">
                            <input id="upload-profile-image-2" type="file" name="file" class="dn upload-profile-image-2"/>
                            <input type="hidden" name="userId" value="{{ $user->id }}">
                            <img class="profile-image img-circle" src="{{ $user->avatar() }}" alt="User Avatar">
                            <span class="ic-edit icon-pencil"></span>
                        </label>
                    </form>
                </div>
                <div class="box-body mt30 tac">
                    <h6 class="m0 mt10 font-weight-normal">آخرین بازدید : {{ $user->lastSeen() }}</h6>
                </div>
            </div>
        </div>

        <form class="col-12" action="{{ route('admin.users.update', $user) }}" method="post">

            @method('patch')
            @csrf

            <div class="box box-info">

                <div class="box-body row">

                    <div class="col-lg-2 col-md-6 col-12 mb30">
                        <div class="input-group">
                            <label>نام کاربری</label>
                            <input class="ltr" type="text" name="username" value="{{ $user->username }}">
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-6 col-12 mb30">
                        <div class="input-group">
                            <label>موبایل@if(empty($user->mobile_verified_at)) <a class="color-danger" href="{{ route('admin.users.verify', ['type' => 'mobile', 'id' => $user->id]) }}"><small>تایید موبایل </small></a> @endif</label>
                            <input class="ltr" type="text" name="mobile" value="{{ $user->mobile }}">
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-6 col-12 mb30">
                        <div class="input-group">
                            <label>ایمیل@if(empty($user->email_verified_at)) <a class="color-danger" href="{{ route('admin.users.verify', ['type' => 'email', 'id' => $user->id]) }}"><small>تایید ایمیل </small></a> @endif</label>
                            <input class="ltr" type="text" name="email" value="{{ $user->email }}">
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-6 col-12 mb30">
                        <div class="input-group">
                            <label for="">رمز عبور</label>
                            <input class="ltr" type="text" placeholder="***********" name="password" value="">
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12 mb30">
                        <div class="input-group">
                            <label for="">نقش ها</label>
                            <select class="select2" name="roles[]" multiple="multiple">
                                @foreach($roles as $record)
                                    <option
                                            {{ ( in_array($record->id, old("roles") ?? $userRoles) ? "selected" : "" ) }}
                                            value="{{ $record->id }}">{{ $record->label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-2 mb30">
                        <div class="input-group">
                            <label>نام</label>
                            <input class="" type="text" name="name" value="{{ old('name') ?? $user->name }}">
                        </div>
                    </div>

                    <div class="col-lg-2 mb30">
                        <div class="input-group">
                            <label>نام خانوادگی</label>
                            <input class="" type="text" name="family" value="{{ old('family') ?? $user->family }}">
                        </div>
                    </div>

                    <div class="col-lg-2 mb30">
                        <div class="input-group">
                            <label>جنسیت</label>
                            <select name="gender">
                                <option value="">انتخاب کنید</option>
                                <option {{ selected(old('gender') ?? $user->gender, 1) }} value="1">مرد</option>
                                <option {{ selected(old('gender') ?? $user->gender, 2) }} value="2">زن</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-6 mb30">
                        <div class="input-group">
                            <label>تاریخ تولد</label>
                            <div class="row">
                                @php
                                    $birthDay   = $birthMonth = 1;
                                    $birthYear  = toEnglish(jDateTime('Y', strtotime(date('Y'))));
                                    if (old('birthDay')) {
                                        $birthDay   = old('birthDay');
                                        $birthMonth = old('birthMonth');
                                        $birthYear  = old('birthYear');
                                    } else {
                                        if (!empty($user->birthday)) {
                                            $birthParts = explode('-', toEnglish(jDateTime('Y-m-d', strtotime($user->birthday))));
                                            $birthDay   = $birthParts[2];
                                            $birthMonth = $birthParts[1];
                                            $birthYear  = $birthParts[0];
                                        }
                                    }
                                @endphp
                                <div class="col-lg-3">
                                    <select name="birthDay">
                                        @for($i = 1; $i <= 31; $i++)
                                            <option {{ selected($i, $birthDay) }} value ="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-lg-5">
                                    <select name="birthMonth">
                                        @foreach(jalaliMonth() as $key => $value)
                                            <option {{ selected($key, $birthMonth) }} value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <select name="birthYear">
                                        @for($i = toEnglish(jDateTime("Y", strtotime(date('Y')))); $i >= 1200; $i--)
                                            <option {{ selected($i, $birthYear) }} value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mb30">
                        <div class="input-group">
                            <label>تلفن ثابت</label>
                            <input class="ltr" type="text" name="metas[phone]" value="{{ (isset(old('metas')['phone']) ? old('metas')['phone'] : (isset($metas['phone']) ? $metas['phone'] : "") ) }}">
                        </div>
                    </div>

                    <div class="col-lg-4 mb30">
                        <div class="input-group">
                            <label>کد ملی</label>
                            <input class="ltr" type="text" name="metas[nationalCode]" value="{{ (isset(old('metas')['nationalCode']) ? old('metas')['nationalCode'] : (isset($metas['nationalCode']) ? $metas['nationalCode'] : "") ) }}">
                        </div>
                    </div>

                    <div class="col-lg-4 mb30">
                        <div class="input-group">
                            <label>کد پستی</label>
                            <input class="ltr" type="text" name="metas[postalCode]" value="{{ (isset(old('metas')['postalCode']) ? old('metas')['postalCode'] : (isset($metas['postalCode']) ? $metas['postalCode'] : "") ) }}">
                        </div>
                    </div>

                    @include('boxes.default.world', [
                        'divClass' => 'col-md-4 col-12',
                        'selectClass' => 'select2',
                        'provinceName' => 'metas[provinceId]',
                        'cityName' => 'metas[cityId]',
                        'townName' => 'metas[townId]',
                        'provinceId' => (isset(old('metas')['postalCode']) ? old('metas')['provinceId'] : (isset($metas['provinceId']) ? $metas['provinceId'] : "") ),
                        'cityId' => (isset(old('metas')['cityId']) ? old('metas')['cityId'] : (isset($metas['cityId']) ? $metas['cityId'] : "") ),
                        'townId' => (isset(old('metas')['townId']) ? old('metas')['townId'] : (isset($metas['townId']) ? $metas['townId'] : "") ),
                    ])

                    <div class="col-12 mt15">
                        <div class="input-group">
                            <label>آدرس</label>
                            <textarea name="metas[address]">{{ (isset(old('metas')['address']) ? old('metas')['address'] : (isset($metas['address']) ? $metas['address'] : "") ) }}</textarea>
                        </div>
                    </div>

                    <?php
                    formValidations([
                        'rules'    => [
                            'metas.phone'        => 'phone',
                            'metas.nationalCode' => 'nationalCode',
                            'metas.provinceId'   => 'int',
                            'metas.cityId'       => 'int',
                            'metas.townId'       => 'int',
                        ],
                        'messages' => [
                            'metas.phone.phone' => 'فرمت تلفن ثابت وارد شده صحیح نمیs باشد',
                            'metas.phone.nationalCode' => 'فرمت کد ملی وارد شده صحیح نمی باشد.',
                            'metas.phone.provinceId' => 'لطفا پارامترهای استاندارد را تغییر ندهید',
                            'metas.phone.cityId' => 'لطفا پارامترهای استاندارد را تغییر ندهید',
                            'metas.phone.townId' => 'لطفا پارامترهای استاندارد را تغییر ندهید',
                        ],
                    ]);
                    ?>

                    <div class="col-12 mt15">
                        <div class="input-group">
                            <label>بیوگرافی</label>
                            <textarea name="metas[biography]">{{ (isset(old('metas')['biography']) ? old('metas')['biography'] : (isset($metas['biography']) ? $metas['biography'] : "") ) }}</textarea>
                        </div>
                    </div>

                </div>

                <div class="box-footer tal">
                    <button class="btn-lg btn-success">ذخیره</button>
                </div>

            </div>

        </form>

    </div>

@endsection

@section('footer-content')
    <link rel="stylesheet" href="/plugins/select2/select2.css">
    <script src="/plugins/select2/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                dir: "rtl",
                closeOnSelect: false,
                onSelect: function () {

                }
            });
        });

    </script>
@endsection
