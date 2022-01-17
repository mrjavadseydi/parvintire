<div class="box box-warning">

    <div class="box-header">
        <h3 class="box-title">{{ $box['title'] }}</h3>
        <div class="box-tools">
            <i class="box-tools-icon icon-minus"></i>
        </div>
    </div>

    <div class="box-body tac">
        <div class="audios">

            <?php $i = 0; ?>
            @foreach($sounds as $sound)
                <div class="audio-item">
                   <span>{{ $sound['title'] }}</span>
                   <span class="audio-remove">حذف</span>
                   <input type="hidden" name="sounds[{{ $i }}][id]" value="{{ $sound['attachmentId'] }}">
                   <input type="hidden" name="sounds[{{ $i }}][path]" value="{{ $sound['path'] }}">
                   <audio id="audio" controls="controls">
                       <source id="audioSource" src="{{ $sound['url'] }}">
                       Your browser does not support the audio format.
                   </audio>
                </div>
                <?php $i++; ?>
            @endforeach

            <style>
                .audio-item {
                    border-bottom: 1px solid #ddd;
                }

                .audio-item:last-child {
                    border-bottom: none;
                }

                .audio-item span {
                    font-size: 11px;
                }

                .audio-item .audio-remove {
                    color: red;
                    cursor: pointer;
                }
            </style>

        </div>
    </div>

    <div class="box-footer tac">
        <a callback="sound" classes="mp3" buttonTitle="ثبت فایل صوتی" class="btn-lg btn-info w100 multiple uploader-open">بارگذاری و انتخاب فایل صوتی</a>
    </div>

    <script>

        $(document).on('click', '.audio-remove', function () {
            $(this).parent().remove();
            sortSounds();
        });

        function sound(data) {

            var audios = $('.audios');

            if ($('input[name=soundsChanged]').length == 0) {
                audios.append('<input type="hidden" name="soundsChanged" value="true">');
            }

            $.each(data['result'], function (i, value) {
                audios.append(
                    '<div class="audio-item">\n' +
                    '   <span>'+value['name']+'</span>\n' +
                    '   <span class="audio-remove">حذف</span>\n' +
                    '   <input type="hidden" id="soundId" value="'+value['id']+'">\n' +
                    '   <input type="hidden" id="soundPath" value="'+value['path']+'">\n' +
                    '   <audio id="audio" controls="controls">\n' +
                    '       <source id="audioSource" src="'+value['url']+'">\n' +
                    '       Your browser does not support the audio format.\n' +
                    '   </audio>\n' +
                    '</div>'
                );
                sortSounds();
            })

        }

        function sortSounds() {
            if ($('.audio-item').length > 0) {
                $('.audio-item').each(function (i, obj) {
                    $(obj).find('#soundId').attr('name', 'sounds['+i+'][id]');
                    $(obj).find('#soundPath').attr('name', 'sounds['+i+'][path]');
                })
            }
        }

    </script>

</div>
