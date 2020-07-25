@extends("admin.{$adminTheme}.master")
@section('title', 'تنظیمات عمومی')

@section('content')

    <?php
    getThemesImages();
    formValidations([
        'rules'    => [
            'options.name'     => 'required',
            'options.mobile'   => 'mobile',
            'options.phone'    => 'phone',
            'options.email'    => 'email',
            'metas.postalCode' => 'postalCode',
        ],
        'messages' => [],
    ]);
    formPermission('generalSetting');
    ?>

    <div class="row">

        <div class="col-12">

            <form class="card" action="{{ route('admin.options.update') }}" method="post">
                @csrf

                <input type="hidden" name="back" value="general">

                <div class="card-body">

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">نام سایت</label>
                        <div class="col-sm-10">
                            <input type="hidden" name="more[name]" value="autoload">
                            <input type="text" name="options[name]" value="{{ siteName() }}" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">کلمات کلیدی سایت</label>
                        <div class="col-sm-10">
                            <input type="hidden" name="more[keywords]" value="autoload">
                            <input type="text" name="options[keywords]"  value="{{ siteKeywords() }}" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">توضیحات سایت</label>
                        <div class="col-sm-10">
                            <input type="hidden" name="more[description]" value="autoload">
                            <textarea name="options[description]" rows="6" class="form-control">{{ siteDescription() }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">نام مدیر سایت</label>
                        <div class="col-sm-10">
                            <input type="hidden" name="more[adminName]" value="autoload">
                            <input type="text" name="options[adminName]"  value="{{ siteAdminName() }}" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">نام خانوادگی مدیر سایت</label>
                        <div class="col-sm-10">
                            <input type="hidden" name="more[adminFamily]" value="autoload">
                            <input type="text" name="options[adminFamily]"  value="{{ siteAdminFamily() }}" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">موبایل</label>
                        <div class="col-sm-10">
                            <input type="hidden" name="more[mobile]" value="autoload">
                            <input type="text" name="options[mobile]"  value="{{ siteMobile() }}" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">تلفن تماس</label>
                        <div class="col-sm-10">
                            <input type="hidden" name="more[phone]" value="autoload">
                            <input type="text" name="options[phone]"  value="{{ sitePhone() }}" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">فکس</label>
                        <div class="col-sm-10">
                            <input type="hidden" name="more[fax]" value="autoload">
                            <input type="text" name="options[fax]"  value="{{ siteFax() }}" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">ایمیل</label>
                        <div class="col-sm-10">
                            <input type="hidden" name="more[email]" value="autoload">
                            <input type="text" name="options[email]"  value="{{ siteEmail() }}" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">کد پستی</label>
                        <div class="col-sm-10">
                            <input type="hidden" name="more[postalCode]" value="autoload">
                            <input type="text" name="options[postalCode]"  value="{{ sitePostalCode() }}" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">آدرس</label>
                        <div class="col-sm-10">
                            <input type="hidden" name="more[address]" value="autoload">
                            <textarea name="options[address]" rows="6" class="form-control">{{ siteAddress() }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">متن کپی رایت</label>
                        <div class="col-sm-10">
                            <input type="hidden" name="more[copyright]" value="autoload">
                            <input type="text" name="options[copyright]"  value="{{ siteCopyright() }}" class="form-control">
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">آدرس ایمیل سرور</label>
                        <div class="col-sm-10">
                            <input type="hidden" name="more[serverEmail]" value="autoload">
                            <input type="text" name="options[serverEmail]"  value="{{ serverEmail() }}" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">رمز ایمیل سرور</label>
                        <div class="col-sm-10">
                            <input type="hidden" name="more[serverEmailPassword]" value="autoload">
                            <input type="text" name="options[serverEmailPassword]"  value="{{ serverEmailPassword() }}" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">لینک ایمیل سرور</label>
                        <div class="col-sm-10">
                            <input type="hidden" name="more[serverEmailHost]" value="autoload">
                            <input type="text" name="options[serverEmailHost]"  value="{{ serverEmailHost() }}" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">پورت ایمیل سرور</label>
                        <div class="col-sm-10">
                            <input type="hidden" name="more[serverEmailPort]" value="autoload">
                            <input type="text" name="options[serverEmailPort]"  value="{{ serverEmailPort() }}" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">نوع ایمیل سرور</label>
                        <div class="col-sm-10">
                            <input type="hidden" name="more[serverEmailType]" value="autoload">
                            <select name="options[serverEmailType]" class="form-control">
                                <option value="tls">tls</option>
                                <option {{ selected(serverEmailType(), 'ssl') }} value="ssl">ssl</option>
                            </select>
                        </div>
                    </div>


                </div>

                <div class="card-footer">
                    <button class="btn btn-success">ذخیره</button>
                </div>

            </form>

        </div>

    </div>

@endsection
