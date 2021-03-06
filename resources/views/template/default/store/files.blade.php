<link rel="stylesheet" href="{{ asset('fonts/sahel/3.3.0/font.css') }}">
<link rel="stylesheet" href="{{ asset('fonts/fontawesome/5.11.12/font.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/javadgholipoor/admin.css') }}">
<script src="{{ asset('plugins/jquery/3.4.1/jquery.min.js') }}"></script>
<div style="margin: 50px 400px;">
    <div class="files-groups {{ $font ?? 'sahel' }}">
        <?php $i = 1;?>
        @foreach($post->filesGroups() as $item)
            @if(count($item['files']) > 0)
                <div class="item {{ $i == 1 ? 'active' : '' }}">
                    <h3>{{ $item['title'] }}</h3>
                    <i class="arrow far fa-angle-left"></i>
                </div>
                <div class="files {{ $i == 1 ? 'active' : '' }}">
                    @foreach($item['files'] as $file)
                        @if($file['file']->status == '2')
                            <div class="file {{ $file['file']->status == '2' ? 'disable' : ($file['file']->can() ? '' : 'lock') }}">
                                <div class="circle">
                                    <span class="counter">{{ $i }}</span>
                                    <span class="lock far fa-clock"></span>
                                </div>
                                <h4>{{ $file['file']->title }}</h4>
                                <div class="details">
                                    @if(!empty($file['file']->note))
                                        <span class="note">{{ $file['file']->note }}</span>
                                    @endif
                                    @if($file['attachment']->duration > 0)
                                        <span>{{ convertSecondToTime($file['attachment']->duration) }}</span>
                                    @endif
                                </div>
                            </div>
                        @else
                            <a href="{{ $file['file']->href() }}" class="file {{ $file['file']->status == '2' ? 'disable' : ($file['file']->can() ? '' : 'lock') }}">
                                <div class="circle">
                                    <span class="counter">{{ $i }}</span>
                                    <span class="lock far fa-lock"></span>
                                </div>
                                <h4>{{ $file['file']->title }}</h4>
                                <div class="details">
                                    @if(!empty($file['file']->note))
                                        <span class="note">{{ $file['file']->note }}</span>
                                    @endif
                                    @if($file['attachment']->duration > 0)
                                        <span>{{ convertSecondToTime($file['attachment']->duration) }}</span>
                                    @endif
                                    <span>{{ $file['file']->title() }}</span>
                                </div>
                            </a>
                        @endif
                        <?php $i++;?>
                    @endforeach
                </div>
            @endif
        @endforeach
    </div>
</div>

<script>
    $('.files-groups .item').click(function () {
        if($(this).hasClass('active')) {
            $(this).removeClass('active');
            $(this).next().removeClass('active');
        } else {
            $(this).addClass('active');
            $(this).next().addClass('active');
        }
    })
</script>
