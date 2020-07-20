@extends("admin.{$adminTheme}.master")
@section('title', 'آپلودر')

@section('content')

    {!! uploader()->relation('reza', 'javad')->validations('jpg|jpeg|png|zip|mpga')->load(); !!}

    <button classes="jpg png jpeg" id="image-cropper" class="uploader-open btn btn-success">آپلودر تک انتخابی</button>
    <button classes="jpg png jpeg"  class="uploader-open btn btn-success multiple">آپلودر چند انتخابی</button>

    <br>
    <br>

    <button
        onComplete="comp1"
        onProgress="prog1"
        url="{{ url('upload') }}"
        data="#progress-1-data"
        data=""
        onAddFiles="onAdd"
        onCompleteAddFiles="onCompleteAdd"
        class="btn btn-success uploader-init">آپلود پراگرس ۱</button>

    <div id="progress-1-data">
        <input type="hidden" name="hi" value="by">
        <input type="hidden" name="by" value="hi">
    </div>

    <script>
        function onAdd(data) {
            console.log(data)
        }
        function onCompleteAdd(data) {
            console.log(data)
        }
        function prog1(data) {
            $('.progress-1').text(data);
        }
        function comp1(status, data) {
            console.log(status)
            console.log(data)
        }
    </script>

    <div class="progress-1">0</div>

    <br>

    <button href="" class="btn btn-success uploader-init">آپلود پراگرس ۲</button>
    <div class="progress-2">0</div>


    <script>

    </script>

@endsection
