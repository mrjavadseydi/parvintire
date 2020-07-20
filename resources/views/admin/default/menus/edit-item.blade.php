@extends("admin.{$adminTheme}.master")
@section('title', 'ویرایش فهرست')

@section('content')

    {!! uploader()->relation('menu', $menuItem->id)->validations([
        'thumbnail' => [
            'in' => 1,
            'key' => 'thumbnail',
            'validations' => 'mimes:png,jpg,jpeg,gif,PNG,JPG,JPEG,GIF|min:0|max:2048'
        ]
    ])->theme('default')->load() !!}

    <div class="row">

        <div class="col-12">
            <form action="{{ route('admin.menu-items.update', ['id' => $menuItem->id]) }}" method="post" class="box-solid box-info">

                @csrf
                @method('patch')
                <input type="hidden" name="menu_id" value="{{ $_GET['menu_id'] ?? '' }}">
                <div class="box-header">
                    <h3 class="box-title">ویرایش فهرست</h3>
                    <div class="box-tools">
                        <i class="box-tools-icon icon-minus"></i>
                    </div>
                </div>

                <div class="box-body">

                    <div class="row">

                        <div class="col-md-4">
                            <div class="input-group">
                                <label for="">عنوان</label>
                                <input type="text" name="title" value="{{ old('title') ?? $menuItem->title }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="input-group">
                                <label for="">منوی مادر</label>
                                <select name="parent" id="">
                                    <option value="">انتخاب کنید</option>
                                    @foreach ($menuItems as $item)
                                        <option {{ selected(old('parent') ?? $menuItem->parent, $item->id) }} value="{{ $item->id }}">{{ $item->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="input-group">
                                <label for="">مرتب سازی</label>
                                <select name="sort" id="">
                                    <option value="">انتخاب کنید</option>
                                    @foreach (sortItems($menuItems = $menuItems->where('parent', $menuItem->parent)->filter()) as $item)
                                        @if($menuItem->id != $item['id'])
                                            <option value="{{ $item['value'] }}">{{ $item['title'] }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 mt25">
                            <div class="input-group">
                                <label for="">لینک</label>
                                <input class="ltr tal" type="text" name="link" value="{{ old('link') ?? $menuItem->link }}">
                                <select name="attributes[target]" id="">
                                    <option value="">بازکردن در همین پنجره</option>
                                    <option value="_blank">بازکردن در پنجره جدید</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 mt25">
                            <div class="input-group">
                                <label for="">آیکون</label>
                                <input type="text" name="icon" value="{{ old('icon') ?? $menuItem->icon }}">
                            </div>
                        </div>

                        <div class="col-md-4 mt25">
                            <div class="input-group">
                                <label for="">کلاس های اضافی</label>
                                <input type="text" name="class" value="{{ old('class') ?? $menuItem->class }}">
                            </div>
                        </div>

                        <div class="col-md-4 mt25">
                            <div class="input-group">
                                <?php
                                $getMenuTypes = getMenuTypes();
                                $type = '';
                                $typeTitle = '';
                                $idsClass = 'd-none';
                                $levelClass = 'd-none';
                                $ids = '';
                                $level = '';
                                if (old('type') ?? $menuItem->type) {
                                    $type = old('type') ?? $menuItem->type;
                                    $typeTitle = $getMenuTypes[$type]['title'];
                                    $typeInputs = $getMenuTypes[$type]['inputs'];
                                    if (isset($typeInputs['ids'])) {
                                        $idsClass = '';
                                    }
                                    if (isset($typeInputs['level'])) {
                                        $levelClass = '';
                                    }
                                    if (!empty($menuItem->data)) {
                                        $data = json_decode($menuItem->data, true);
                                        if (isset($data['ids'])) {
                                            $ids = implode(',', $data['ids']);
                                        }
                                        if (isset($data['level'])) {
                                            $level = $data['level'];
                                        }
                                    }
                                }

                                ?>
                                <label for="">فیلتر پیشرفته</label>
                                <select name="type">
                                    <option value="">انتخاب کنید</option>
                                    @foreach ($getMenuTypes as $key => $value)
                                        <option {{ selected($key, $type) }} value="{{ $key }}">{{ $value['title'] }}</option>
                                    @endforeach
                                </select>
                                @foreach ($getMenuTypes as $key => $value)
                                    @foreach ($value['inputs'] as $input => $val)
                                        <input disabled type="hidden" name="{{ $key }}-{{ $input }}" value="{{ $val['title'] }}">
                                    @endforeach
                                @endforeach
                                <label class="ids-inputs type-inputs {{ $idsClass }} db mt15" id="ids-label">{{ $typeTitle }}</label>
                                <input class="ids-inputs ltr type-inputs {{ $idsClass }} " placeholder="1,2,3,..." type="text" name="ids" value="{{ old('ids') ?? $ids }}">
                                <label class="level-inputs type-inputs {{ $levelClass }} db mt15" id="level-label">سطح</label>
                                <select class="level-inputs type-inputs {{ $levelClass }} " name="level">
                                    @for($i = 1; $i <= 10; $i++)
                                        <option {{ selected($i, old('level') ?? $level) }} value="{{ $i }}">سطح {{ $i }}</option>
                                    @endfor
                                </select>
                                <script>
                                    $(document).on('change', 'select[name=type]', function () {
                                        var val = $(this).val();
                                        $('.type-inputs').addClass('d-none');
                                        var idsLabel = $('input[name='+val+'-ids]').val();
                                        var levelLabel = $('input[name='+val+'-level]').val();
                                        $('#ids-label').text(idsLabel);
                                        $('#level-label').text(levelLabel);
                                        if (idsLabel != undefined) {
                                            $('.ids-inputs').removeClass('d-none');
                                        }
                                        if (levelLabel != undefined) {
                                            $('.level-inputs').removeClass('d-none');
                                        }
                                    });
                                </script>
                            </div>
                        </div>

                        <div class="col-md-4 mt25">
                            <div class="input-group">
                                <label for="">فعال/غیرفعال</label>
                                <select name="active">
                                    <option value="1">فعال</option>
                                    <option {{ selected('0', old('active') ?? $menuItem->active) }} value="0">غیرفعال</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 mt25">
                            <div class="input-group">
                                <label for="">تصویر</label>
                                <input class="ltr tal" type="hidden" name="image" value="{{ old('image') ?? (empty($menuItem->image) ? '' : $menuItem->image) }}">
                                <span key="thumbnail" callback="menuImage" buttonTitle="ثبت تصویر" class="btn btn-info uploader-open">باگذاری تصویر</span>
                                <img style="vertical-align: text-top;" id="menuImage" width="150px" src="{{ empty(old('image')) ? (empty($menuItem->image) ? '' : url($menuItem->image)) : url(old('image')) }}">
                                <script>
                                    function menuImage(data){
                                        $('#menuImage').attr('src', data['result']['url']);
                                        $('input[name=image]').val(data['result']['path']);
                                    }
                                </script>
                            </div>
                        </div>

                        <div class="col-md-12 mt25">
                            <div class="input-group">
                                <label for="">محتوا</label>
                                <textarea id="ckeditor" name="content">{{ old('content') ?? $menuItem->content }}</textarea>
                                <script>
                                    CKEDITOR.replace( 'ckeditor' ,{
                                        height: '250px',
                                        filebrowserUploadUrl : '/uploads/ckeditor',
                                        filebrowserImageUploadUrl :  '/uploads/ckeditor'
                                    });
                                </script>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="box-footer tal">
                    <button class="btn-lg btn-success">ذخیره</button>
                </div>

            </form>
        </div>

    </div>

    <style>
        .d-none {
            display: none;
        }
    </style>

@endsection
