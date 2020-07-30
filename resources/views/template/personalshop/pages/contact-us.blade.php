@extends(includeTemplate('master'))
@section('title', 'تماس با ما')
@section('keywords', siteKeywords())
@section('description', siteDescription())
@section('ogType', 'website')
@section('ogTitle', 'تماس با ما')
@section('ogDescription', siteDescription())
@section('ogImage', siteLogo())
@section('content')
    <div class="product py-3 contact-us">
        <div class="container-fluid px-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">صفحه اصلی</a></li>
                    <li class="breadcrumb-item active" aria-current="page">تماس با ما</li>
                </ol>
            </nav>
            @include(includeTemplate('divider.2'), ['title' => 'تماس با ما'])
            <div class="bg-white border rounded p-3 my-3">
                <p class="text-justify">شما میتوانید، انتقاد و یا سوالات خود را از طریق این فرم برای ما ارسال نمایید، کارشناسان نخستین ثبت آن را بررسی کرده و در اسرع وقت با شما تماس خواهند گرفت. شماره تماس کارشناسان و مشاوران نیز در پایین ذکر شده است. شما میتوانید پیشنهاد، انتقاد و یا سوالات خود را از طریق فرم زیر برای ما ارسال کنید</p>
                <div class="py-3">
                    @include(includeTemplate('divider.3'), ['title' => 'با ما در ارتباط باشید'])
                </div>
                <form action="{{  route('addComment') }}" method="post" clear="#c1, #c2, #c3, #c4" class="row ajaxForm ajaxForm-iziToast">
                    <div class="col-md-5">
                        <div class="d-flex">
                            <i class="i far fa-phone-volume"></i>
                            <div class="d-flex flex-column align-self-center mr-3">
                                <h5>تلفن تماس</h5>
                                <h6>{{ sitePhone() }}</h6>
                            </div>
                        </div>
                        <div class="d-flex my-5">
                            <i class="i blue far fa-envelope"></i>
                            <div class="d-flex flex-column align-self-center mr-3">
                                <h5>ایمیل</h5>
                                <h6>{{ siteEmail() }}</h6>
                            </div>
                        </div>
                        <div class="d-flex">
                            <i class="i pink far fa-map-marker-alt"></i>
                            <div class="d-flex flex-column align-self-center mr-3">
                                <h5>آدرس</h5>
                                <h6>{{ siteAddress() }}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <input type="hidden" name="type" value="2">
                        <div class="row px-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>نام و نام خانوادگی</label>
                                    <input id="c1" type="text" class="form-control" name="name_family">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>ایمیل</label>
                                    <input id="c2" type="text" class="form-control" name="email">
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>موضوع پیام</label>
                                <input id="c3" type="text" class="form-control" name="subject">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>متن پیام</label>
                                <textarea name="comment" class="form-control" id="c4" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                        <div class="col text-left">
                            <button class="btn btn-warning px-5">ارسال</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
