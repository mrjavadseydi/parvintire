@extends(includeTemplate('profile.master'))
@section('title', 'پروفایل من')
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ $user->name }}</li>
@endsection
@section('main')
    <form action="{{ route('profile.update') }}" method="post" class="ajaxForm ajaxForm-iziToast">
            <div class="form-row">
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label class="pb-2" for="name">نام</label>
                        <input type="text" class="form-control" name="name" value="{{ $user->name }}">
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label class="pb-2" for="name">نام خانوادگی</label>
                        <input type="text" class="form-control" name="family" value="{{ $user->family }}">
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label class="pb-2" for="name">جنسیت</label>
                        <select name="gender" class="form-control">
                            <option value="">انتخاب کنید</option>
                            <option {{ selected('1', $user->gender) }} value="1">مرد</option>
                            <option {{ selected('2', $user->gender) }} value="2">زن</option>
                        </select>
                    </div>
                </div>
                <div class="happy-day col-12 mb-3">
                    <label for="" class="mb-2">تاریخ تولد</label>
                    <div class="row">
                        <div class="col-md-4 col-lg-4 my-3 my-md-0">
                            <select name="birthDay" class="form-control">
                                @for($i = 1; $i <= 31; $i++)
                                    <option {{ selected($i, $day) }} value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4 col-lg-4 my-3 my-md-0">
                            <select name="birthMonth" class="form-control">
                                @foreach(jalaliMonth() as $i => $title)
                                    <option {{ selected($i, $month) }} value="{{ $i }}">{{ $title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 col-lg-4 my-3 my-md-0">
                            <select name="birthYear" class="form-control">
                                <?php $getYear = toEnglish(jDateTime('Y', strtotime('now')));?>
                                @for($i = $getYear - 200; $i <= $getYear; $i++)
                                    <option {{ selected($i, $year) }} value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="email">عنوان شغلی</label>
                        <input value="{{ $metas['job']['value'] ?? '' }}" name="metas[job]" class="form-control" id="email">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="mb-1" for="email">درباره من</label>
                        <textarea name="metas[biography]" cols="30" rows="4"
                                  placeholder="توضیحاتی درباره حرفه شما"
                                  class="form-control text-white text-dark">{{ $metas['biography']['value'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>
            <button class="btn btn-block btn-outline-success mt-3">ثبت تغییرات</button>
        </form>
@endsection
