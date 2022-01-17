@extends("admin.{$adminTheme}.master")
@section('title', "فیلتر جستجو")

@section('content')

    <div class="col-lg-12">

        <form action="{{ route('admin.attributes.search.update') }}" method="post" class="box-solid box-primary">

            <div class="box-header">
                <h3 class="box-title">@yield('title')</h3>
                <div class="box-tools">
                    <i class="box-tools-icon icon-minus"></i>
                </div>
            </div>

            <div class="box-body">

                @csrf

                <div class="row">
                    @foreach($data as $postType => $attrs)
                        <div class="col-md-4 mb10">
                            <h3 class="m0">{{ $postType }}</h3>
                            <div class="mr15">
                                @foreach($attrs as $attr)
                                    @if(isset($attr['keys']))
                                        <div class="mr15">
                                            @foreach($attr['keys'] as $key)
                                                @if(isset($key['values']))
                                                    <ul class="checkbox mr15">
                                                        @foreach($key['values'] as $value)
                                                            <?php $v = "{$attr['id']}-{$key['id']}-{$value['id']}"; ?>
                                                            <li><label style="cursor: pointer;" for="for-{{ $postType }}-{{ $v }}"><input {{ in_array($v, $old) ? 'checked' : '' }} id="for-{{ $postType }}-{{ $v }}" value="{{ $v }}" name="attributes[{{ $postType }}][]" type="checkbox"> {{ $attr['title'] }} - {{ $key['title'] }} - {{ $value['title'] }}</label></li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>

            <div class="box-footer tal">
                <button class="btn-lg btn-success">ذخیره</button>
            </div>

        </form>

    </div>

@endsection
