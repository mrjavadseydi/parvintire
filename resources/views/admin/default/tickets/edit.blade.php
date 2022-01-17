@extends("admin.{$adminTheme}.master")
@section('title', 'ویرایش ' . $ticket->subject)

@section('content')

    <div class="col-12">

        <div class="box-solid box-primary">
        
            <div style="background-color: #5f8fc4" class="box-header">
                <h3 style="font-weight: normal" class="box-title">ایجاد شده توسط {{ $name }} در تاریخ {{ jDateTime('Y/m/d', strtotime($ticket->created_at)) }} ساعت {{ jDateTime('H:i', strtotime($ticket->created_at)) }}</h3>
                <div class="box-tools">
                    <span style="color: white;">{{ config("comment.status.ticket.{$ticket->status}.title") }}</span>
                </div>
            </div>

            <div class="box-body">
                <strong>موضوع : {{ $ticket->subject }}</strong>
                <p>{!! $ticket->comment !!}</p>
                <div class="tal">
                    @if($ticket->mobile != null)
                        <label style="display: inline-block; background: #e9e9e9; border-radius: 3px; padding: 5px;">{{ $ticket->mobile }}</label>
                    @endif
                    @if($ticket->email != null)
                        <label style="display: inline-block; background: #e9e9e9; border-radius: 3px; padding: 5px;">{{ $ticket->email }}</label>
                    @endif
                    @foreach ($ticket->metas() as $meta)
                        <label style="display: inline-block; background: #e9e9e9; border-radius: 3px; padding: 5px; direction: ltr;">{{ $meta->key }}: {{ $meta->value }}</label>
                    @endforeach
                </div>
            </div>

        </div>

        @foreach ($tickets as $item)
            <div class="box box-{{ $item['type'] == 'answer' ? 'success' : 'pink' }}">

                <div class="box-header">
                    <h3 class="box-title">{{ $item['name'] }}</h3>
                    <div class="box-tools">
                        <span class="ltr">{{ $item['time'] }} {{ $item['date'] }}</span>
                    </div>
                </div>

                <div class="box-body">
                    <p>{{ $item['comment'] }}</p>
                    <div class="tal">
                        @foreach ($item['metas'] as $meta)
                            <label style="display: inline-block; background: #e9e9e9; border-radius: 3px; padding: 5px; direction: ltr;">{{ $meta->key }}: {{ $meta->value }}</label>
                        @endforeach
                    </div>
                </div>

            </div>
        @endforeach

        <form onSuccess="onSuccess" onerror="onError" method="post" action="{{ route('addComment') }}" style="border-color: #5f8fc4" class="ajaxForm box box-primary">

            @csrf
            <input type="hidden" name="type" value="2">
            <input type="hidden" name="parent" value="{{ $ticket->id }}">
            <input type="hidden" name="subject" value="1">
            <input type="hidden" name="status" value="2">

            <div class="box-header">
                <h3 class="box-title">پاسخ</h3>
            </div>

            <div class="box-body">
                <textarea name="comment" cols="30" rows="10"></textarea>
            </div>

            <div class="box-footer tal">
                <button class="btn-lg btn-success">ارسال پاسخ</button>
            </div>

        </form>

        <script>
            function onSuccess(response) {
                if (response.status == 'success') {
                    location.reload();
                } else {
                    iziToast.error({
                        title: '',
                        message: response.message,
                        position: 'center',
                        rtl: true,
                    });
                }
            }

            function onError(data) {

            }
        </script>

    </div>

    <style>
        p {
            color: #5b5b5b;
        }

        textarea {
            width: 100%;
        }
    </style>

@endsection
