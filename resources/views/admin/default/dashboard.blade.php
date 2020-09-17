@extends("admin.{$adminTheme}.master")
@section('title', 'پنل مدیریت')

@section('content')

    <?php
        echo uploader()
                ->relation('ftp', 'ftp')
                ->addKey('i1', 1, 'mimes:jpg,png,gif,jpeg')
                ->addKey('i2', 2, 'mimes:jpg,png,gif,jpeg')
                ->addKey('publicFtp', 3, 'mimes:jpg,png,gif,jpeg')
                ->addKey('3_path', 3, 'mimes:jpg,png,gif,jpeg', null, [], 'uploads/users/1_my_token/1_2_motion', false, false)
                ->addKey('rootFtp', 4, 'mimes:jpg,png,gif,jpeg')
                ->addKey('publicFtpToken', 5, 'mimes:jpg,png,gif,jpeg', null, [], 'userProjects/6548_bestLogoMotionsForMe_test/654987897654', false, false)
                ->load();
    ?>
    <button key="i1" extensions="all" class="uploader-open btn btn-success">1</button>
    <button key="i2" extensions="all" class="uploader-open btn btn-success">2</button>
    <button key="publicFtp" extensions="all" class="uploader-open btn btn-success">3</button>
    <button key="3_path" extensions="all" class="uploader-open btn btn-success">3_to_my_path</button>
    <button key="rootFtp" extensions="all" class="uploader-open btn btn-success">4</button>
    <button key="publicFtpToken" extensions="all" class="uploader-open btn btn-success">5</button>

@endsection
