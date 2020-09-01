@extends(includeEmail('master'))
@section('content')
    <span>{{ $name }} عزیز</span>
    <br>
    <span>سفارش شما با موفقیت ثبت شد</span>
    <br>
    <span>شماره پیگیری : <b style="color: deeppink">{{ $referenceId }}</b></span>
@endsection
