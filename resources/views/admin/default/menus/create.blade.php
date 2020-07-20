@extends("admin.{$adminTheme}.master")
@section('title', 'افزودن فهرست')

@section('content')

    <div class="row">
        <div class="col-lg-4 col-12">
            <form action="{{ route('admin.menus.store') }}" method="post" class="box-solid box-info">

                @csrf
                <div class="box-header">
                    <h3 class="box-title">افزودن فهرست</h3>
                    <div class="box-tools">
                        <i class="box-tools-icon icon-minus"></i>
                    </div>
                </div>

                <div class="box-body">

                    <div class="input-group">
                        <label for="">عنوان</label>
                        <input type="text" name="title" value="{{ old('title') }}">
                    </div>

                    <div class="input-group">
                        <label style="margin-bottom: 4px;display: block;">جایگاه ها</label>
                        <select class="places w100" name="places[]" multiple>
                            @foreach ($places as $key => $values)
                                <optgroup label="{{ ($key == 'admin' ? 'قالب مدیریت' : 'قالب سایت') }}">
                                    @foreach ($values as $place => $item)
                                        <option {{ in_array($place, old('places') ?? []) ? 'selected' : '' }} value="{{ $place }}">{{ $item['title'] }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                    @if($languages->count() > 0)
                        <div class="pb10 input-group">
                            <i class="icon-link2"></i>
                            <label>زبان</label>
                            <select name="lang">
                                @foreach($languages as $lang)
                                    <option {{ selected(old('lang'), $lang->lang) }} value="{{ $lang->lang }}">{{ $lang->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <script>
                        $('.places').select2({
                            dir: "rtl",
                            tags: true
                        });
                    </script>

                </div>

                <div class="box-footer tal">
                    <button class="btn-lg btn-success">ذخیره</button>
                </div>

            </form>
        </div>
    </div>

@endsection
