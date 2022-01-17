@extends("admin.{$adminTheme}.master")
@section('title', 'ویرایش نقش')

@section('content')

    <div class="col-lg-12">

        <form action="{{ route('admin.roles.update', ['id' => $role->id]) }}" method="post"
              class="box-solid box-primary">

            <div class="box-header">
                <h3 class="box-title">@yield('title')</h3>
                <div class="box-tools">
                    <i class="box-tools-icon icon-minus"></i>
                </div>
            </div>

            <div class="box-body">

                @csrf
                @method('patch')

                <div class="row">

                    <div class="col-12">

                        <div class="row">

                            <div class="col-md-4">
                                <div class="input-group">
                                    <label>عنوان</label>
                                    <input autocomplete="off" type="text" name="label"
                                           value="{{ old('label') ?? $role->label }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="input-group">
                                    <label>نقش
                                        <small>(فرمت مجاز : کاراکترهای انگلیسی)</small>
                                    </label>
                                    <input autocomplete="off" type="text" name="name"
                                           value="{{ old('name') ?? $role->name }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="input-group">
                                    <label>پشتیبان تیکت</label>
                                    <select name="ticketDepartment">
                                        <option value="0">غیرفعال</option>
                                        <option {{ $ticketDepartment ? 'selected' : '' }} value="1">فعال</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 mt20">
                                <div class="input-group">
                                    <label>نقش هایی که من میتوانم به بقیه اختصاص دهم</label>
                                    <select name="canSet[]" class="select2-multiple w100" multiple>
                                        <?php
                                        $canSetSelfRole = false;
                                        if (auth()->user()->can('canSetSelfRole'))
                                            $canSetSelfRole = true;
                                        ?>
                                        @foreach($roles as $roleRecord)
                                            <?php
                                            $check = (in_array($roleRecord->id, old("canSet") ?? $roleLevels) ? true : false );
                                            $checkId = false;
                                            if ($roleRecord->id != $role->id)
                                                $checkId = true;
                                            else
                                                if ($canSetSelfRole)
                                                    $checkId = true;
                                            ?>
                                            @if($checkId)
                                                <option {{ ($check ? 'selected' : '' ) }} value={{ $roleRecord->id }}>{{ $roleRecord->label }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 mt20">
                                <div class="input-group">
                                    <label>نقش هایی که میتوانند من را به بقیه اختصاص دهند</label>
                                    <select name="canSetMe[]" class="select2-multiple w100" multiple>
                                        @foreach($roles as $roleRecord)
                                            <?php
                                                $check = (old('canSetMe') ? (in_array($roleRecord->id, old("canSetMe")) ? true : false ) : $roleRecord->isCanSet($role));
                                            ?>
                                            @if($roleRecord->id != $role->id)
                                                <option {{ ($check ? 'selected' : '' ) }} value={{ $roleRecord->id }}>{{ $roleRecord->label }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>

                        <hr>

                        <div class="col-12 mt20">
                            <div class="input-group">
                                <label>مجوز ها</label>
                            </div>
                            <ul class="checkbox row">
                                <li class="col-lg-3 col-md-6">
                                    <label><input id="all" class="checked-all-permissions" type="checkbox"> انتخاب همه</label>
                                </li>
                                @foreach($treeViewPermissions as $permission)
                                    <h4 class="mb0" style="width: 100%">{{ $permission['title'] }}</h4>
                                    <?php
                                    $checkedAllClass = "checked-all-{$permission['id']}";
                                    $check = (in_array($permission['id'], old("permissions") ?? $rolePermissions) ? true : false );
                                    ?>
                                    <li class="col-lg-3 col-md-6">
                                        <label><input id="{{ $checkedAllClass }}" class="checked-all-permissions" type="checkbox"> انتخاب همه</label>
                                    </li>
                                    <li class="col-lg-3 col-md-6">
                                        <label
                                            style="{{ ($check ? '' : 'color: #ea4b4b !important;' ) }}"
                                            for="for-{{ $permission['id'] }}">
                                            <input {{ ($check ? 'checked' : '' ) }}
                                                   class="{{ $checkedAllClass }}"
                                                   id="for-{{ $permission['id'] }}"
                                                   value="{{ $permission['id'] }}"
                                                   name="permissions[]"
                                                   type="checkbox"> {{ $permission['title'] }}</label>
                                    </li>
                                    @if(isset($permission['list']))
                                        @foreach($permission['list'] as $p)
                                            <?php
                                            $check = (in_array($p['id'], old("permissions") ?? $rolePermissions) ? true : false );
                                            ?>
                                            <li class="col-lg-3 col-md-6">
                                                <label
                                                    style="{{ ($check ? '' : 'color: #ea4b4b !important;' ) }}"
                                                    for="for-{{ $p['id'] }}">
                                                    <input {{ ($check ? 'checked' : '' ) }}
                                                           class="{{ $checkedAllClass }}"
                                                           id="for-{{ $p['id'] }}"
                                                           value="{{ $p['id'] }}"
                                                           name="permissions[]"
                                                           type="checkbox"> {{ $p['title'] }}</label>
                                            </li>
                                        @endforeach
                                    @endif
                                @endforeach
                            </ul>
                        </div>

                        <hr>

                        <div class="col-12 mt20">

                            <div class="input-group">
                                <i class="icon-check"></i>
                                <label>نوع مطالب</label>
                            </div>
                            @foreach($postTypes as $record)
                                <h3 class="mb0">{{ $record->label }}</h3>
                                <ul class="checkbox row">
                                    <li class="col-lg-2 col-md-6">
                                        <label for="for-post-type-{{ $record->type }}-all">
                                            <input id="for-post-type-{{ $record->type }}-all"
                                                   type="checkbox"> انتخاب همه</label>
                                    </li>
                                    <script>
                                        $('#for-post-type-{{ $record->type }}-all').change(function () {
                                            $('.for-post-type-{{ $record->type }}-all').prop('checked', $(this).prop('checked'))
                                        });
                                    </script>
                                    @foreach ($allPostTypesPermissions as $key => $value)
                                        @if(isset($postTypesPermissions[$key]))
                                            @if(in_array($record->type, $postTypesPermissions[$key]))
                                                <?php
                                                $check = (in_array($record->type, (isset(old("postTypes")[$record->type]) ? old("postTypes")[$record->type] : (isset($rolePostTypesPermissions[$key]) ? $rolePostTypesPermissions[$key] : []))) ? true : false );
                                                ?>
                                                <li class="col-lg-2 col-md-6">
                                                    <label
                                                        style="{{ ($check ? '' : 'color: #ea4b4b !important;' ) }}"
                                                        for="for-post-type-{{ $record->type }}-{{ $key }}">
                                                        <input {{ ($check ? 'checked' : '' ) }}
                                                           class="for-post-type-{{ $record->type }}-all"
                                                           id="for-post-type-{{ $record->type }}-{{ $key }}"
                                                           value="{{ $key }}"
                                                           name="postTypes[{{ $record->type }}][]"
                                                           type="checkbox"> {{ $value['title'] }}</label>
                                                </li>
                                            @endif
                                        @endif
                                    @endforeach
                                </ul>

                                @php
                                    $categoryList = $categories->where('post_type', $record->type)->filter();
                                @endphp
                                @if($categoryList->count() > 0)
                                    <div class="input-group">
                                        <h5 class="mb0">دسته‌بندی {{ $record->total_label }}</h5>
                                        <select class="select2" name="categories[{{ $record->type }}][]" multiple="multiple">
                                            <?php echo selectOptions((isset(old('categories')[$record->type]) ? old('categories')[$record->type] : $roleCategories) ,$categoryList);?>
                                        </select>
                                    </div>
                                @endif

                            @endforeach

                        </div>

                        <hr>

                        <div class="col-12 mt20">

                            <div class="input-group">
                                <i class="icon-card_membership"></i>
                                <label>باکس های مطالب</label>
                            </div>
                            <ul class="checkbox row">
                                <li class="col-lg-4 col-md-6">
                                    <label
                                        style="{{ ($check ? '' : 'color: #ea4b4b !important;' ) }}"
                                        for="for-all-boxes">
                                        <input id="for-all-boxes" type="checkbox"> انتخاب همه</label>
                                </li>
                                @foreach($postBoxes as $name => $value)
                                    <?php
                                    $check = (in_array($name, old("postBoxes") ?? $rolePostBoxes) ? true : false );
                                    ?>
                                    <li class="col-lg-4 col-md-6">
                                        <label
                                            style="{{ ($check ? '' : 'color: #ea4b4b !important;' ) }}"
                                            for="for-{{ $name }}">
                                            <input {{ ($check ? 'checked' : '' ) }}
                                            id="for-{{ $name }}"
                                            value="{{ $name }}"
                                            name="postBoxes[]"
                                            type="checkbox"> {{ $value['title'] }}</label>
                                    </li>
                                @endforeach
                            </ul>

                            <script>
                                $(document).on('change', '#for-all-boxes', function () {
                                    if ($(this).prop('checked')) {
                                        $(this).parent().parent().parent().find('input').prop('checked', true);
                                    } else {
                                        $(this).parent().parent().parent().find('input').prop('checked', false);
                                    }
                                });
                            </script>

                        </div>

                        <hr>

                        @if(!empty($countries))
                            <div class="col-12 mt20">
                                <div class="input-group">
                                    <h5 class="mb0">کشور</h5>
                                    <select class="select2" name="countries[]" multiple="multiple">
                                        @foreach ($countries as $country)
                                            <option {{ (in_array($country->id, old("countries") ?? $roleCountries) ? "selected" : "") }} value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        @if(!empty($provinces))
                            <div class="col-12 mt20">
                                <div class="input-group">
                                    <h5 class="mb0">استان</h5>
                                    <select class="select2" name="provinces[]" multiple="multiple">
                                        @foreach ($provinces as $province)
                                            <option {{ (in_array($province->id, old("provinces") ?? $roleProvinces) ? "selected" : "") }} value="{{ $province->id }}">{{ $province->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        @if(!empty($cities))
                            <div class="col-12 mt20">
                                <div class="input-group">
                                    <h5 class="mb0">شهرستان</h5>
                                    <select class="select2" name="cities[]" multiple="multiple">
                                        @foreach ($cities as $city)
                                            <option {{ (in_array($city->id, old("cities") ?? $roleCities) ? "selected" : "") }} value="{{ $city->id }}">{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        @if(!empty($towns))
                            <div class="col-12 mt20">
                                <div class="input-group">
                                    <h5 class="mb0">شهر</h5>
                                    <select class="select2" name="towns[]" multiple="multiple">
                                        @foreach ($towns as $town)
                                            <option {{ (in_array($town->id, old("towns") ?? $roleTowns) ? "selected" : "") }} value="{{ $town->id }}">{{ $town->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                    </div>

                </div>

            </div>

            <div class="box-footer tal">
                <button type="submit" class="btn-lg btn-success">ذخیره</button>
            </div>

        </form>

    </div>

@endsection

@section('footer-content')
    <link rel="stylesheet" href="{{ plugins('select2/select2.css') }}">
    <script src="{{ plugins('select2/select2.min.js') }}"></script>
    <script>

        $(document).on('click', '.checked-all-permissions', function () {
            var parent = $(this).closest('.checkbox');
            var cls = $(this).attr('id');
            if ($(this).prop('checked')) {
                if (cls == 'all') {
                    parent.find('input').prop('checked', true);
                } else {
                    parent.find('input.'+cls).prop('checked', true);
                }
            } else {
                if (cls == 'all') {
                    parent.find('input').prop('checked', false);
                } else {
                    parent.find('input.'+cls).prop('checked', false);
                }
            }
        });

        $(document).ready(function() {

            $('.select2').select2({
                dir: "rtl",
                closeOnSelect: false,
                onSelect: function () {

                }
            });

            $('.select2-multiple').select2({
                dir: "rtl",
                tags: true,
                closeOnSelect: false,
                onSelect: function () {

                }
            });

        });

    </script>
@endsection
