<div id="alerts">
    @if(count($errors) > 0)

        {{--<script>--}}
        {{--$(document).ready(function () {--}}
        {{--@foreach($errors->getMessages() as $key => $error )--}}
        {{--$('[name="{{ $key }}"], [name="{{ $key }}[]"]').after('<span class="validation">{{ $error[0] }}</span>')--}}
        {{--$('[name="{{ $key }}"], [name="{{ $key }}[]"]').keypress(function () {--}}
        {{--$(this).next('span').remove();--}}
        {{--});--}}
        {{--@endforeach--}}
        {{--});--}}
        {{--</script>--}}

        <div class="alert alert-danger">
            <h4>
                <i class="icon-block"></i>
                <span>خطا</span>
            </h4>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session()->has('success'))
        <div class="alert alert-success">
            <i class="icon-check"></i>
            {{ session()->get('success') }}
        </div>
    @elseif(session()->has('warning'))
        <div class="alert alert-warning">
            <i class="icon-warning"></i>
            {{ session()->get('warning') }}
        </div>
    @elseif(session()->has('error'))
        <div class="alert alert-danger">
            <i class="icon-block"></i>
            {{ session()->get('error') }}
        </div>
    @elseif(session()->has('info'))
        <div class="alert alert-info">
            <i class="icon-info"></i>
            {{ session()->get('info') }}
        </div>
    @endif
</div>

@if (Session::has('sweet_alert.alert'))
    <script>
        swal({!! Session::pull('sweet_alert.alert') !!});
    </script>
@endif
