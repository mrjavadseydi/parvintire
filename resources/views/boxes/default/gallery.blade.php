<div class="box box-info">

    <div class="box-header">
        <h3 class="box-title">{{ $box['title'] }}</h3>
        <div class="box-tools">
            <i class="box-tools-icon icon-minus"></i>
        </div>
    </div>

    <div class="box-body tac">
        <div id="gallery" style="display: flex; flex-wrap: wrap; justify-content: space-around;">

            <?php $i = 0; ?>
            @foreach ($gallery as $item)
                <div class="gallery-item">
                    <img src="{{ url($item->value) }}" alt="{{ $item->value }}">
                    <span class="remove">حذف</span>
                    <input type="hidden" name="gallery[{{ $i }}][id]" value="{{ $item->more }}">
                    <input type="hidden" name="gallery[{{ $i }}][path]" value="{{ $item->value }}">
                </div>
                <?php $i++; ?>
            @endforeach

            <style>
                #gallery {
                    display: flex;
                    flex-wrap: wrap;
                    justify-content: space-around;
                }

                .gallery-item {
                    width: 105px;
                    height: 105px;
                    margin-bottom: 10px;
                    position: relative;
                    overflow: hidden;
                }

                .gallery-item img {
                    width: 100%;
                    height: 100%;
                }

                .gallery-item .remove {
                    display: none;
                    cursor: pointer;
                    position: absolute;
                    left: 0;
                    top: 0;
                    right: 0;
                    text-align: center;
                    line-height: 105px;
                    background: #00000061;
                    color: white;
                }

                .gallery-item:hover .remove {
                    display: block;
                }
            </style>

        </div>
    </div>

    <div class="box-footer tac">
        <a key="gallery" callback="gallery" buttonTitle="ثبت تصاویر برای گالری" class="btn-lg btn-info w100 uploader-open multiple">بارگذاری و انتخاب تصاویر گالری</a>
    </div>

    <script>

        $(document).on('click', '.gallery-item', function () {
            $(this).remove();
            var gallery = $('#gallery');
            if ($('input[name=galleryChanged]').length == 0) {
                gallery.append('<input type="hidden" name="galleryChanged" value="true">');
            }
            sortGallery();
        });

        function gallery(data) {
            var gallery = $('#gallery');
            if ($('input[name=galleryChanged]').length == 0) {
                gallery.append('<input type="hidden" name="galleryChanged" value="true">');
            }
            $.each(data['result'], function (i, value) {
                var thumbnail = value['thumbnail'];
                gallery.append(
                    '<div class="gallery-item">' +
                    '   <img src="'+thumbnail+'" alt="'+thumbnail+'">\n' +
                    '   <span class="remove">حذف</span>\n' +
                    '   <input type="hidden" id="galleryId" value="'+value['id']+'">\n' +
                    '   <input type="hidden" id="galleryPath" value="'+value['path']+'">\n' +
                    '</div>'
                );
            })
            sortGallery();
        }

        function sortGallery() {
            if ($('.gallery-item').length > 0) {
                $('.gallery-item').each(function (i, obj) {
                    $(obj).find('#galleryId').attr('name', 'gallery['+i+'][id]');
                    $(obj).find('#galleryPath').attr('name', 'gallery['+i+'][path]');
                })
            }
        }

    </script>

</div>
