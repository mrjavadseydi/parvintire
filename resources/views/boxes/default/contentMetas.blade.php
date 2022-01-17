@if(isset($postTypeMetas[$key]))
    <div class="box box-info">
        <div class="box-header">
            <h3 class="box-title">{{ $box['title'] }}</h3>
            <div class="box-tools">
                <i class="box-tools-icon icon-minus"></i>
            </div>
        </div>
        <div class="box-body">
            <?php $m = $metas->where('key', $key)->filter();?>
            @foreach($postTypeMetas[$key] as $item)
                <?php $v = $m->where('more', $item['key'])->first(); ?>
                <div class="input-group">
                    <label>{{ $item['value'] }}</label>
                    <textarea id="post-type-metas-{{ $item['key'] }}" name="postTypeMetas[{{ $key }}][{{ $item['key'] }}]">{{ $v == null ? '' : $v->value }}</textarea>
                    <script>
                        c = null;
                        window['c{{ $item['key'] }}'] = CKEDITOR.replace('post-type-metas-{{ $item['key'] }}' ,{
                            height: '250px'
                        });
                        window['c{{ $item['key'] }}'].ui.addButton('jghAddImage', {
                            label: "افزودن تصویر",
                            command: 'CmAddImage',
                            toolbar: 'insert',
                            icon: '/plugins/ckeditor/4.1/jgh/add-image.png'
                        });
                        window['c{{ $item['key'] }}'].addCommand("CmAddImage", {
                            exec: function (edt) {
                                c = 'c{{ $item['key'] }}';
                                $('#ckEditor-addImage-cm').click();
                            }
                        });
                        window['c{{ $item['key'] }}'].ui.addButton('jghCopyUrl', {
                            label: "کپی لینک",
                            command: 'CmLinkCopy',
                            toolbar: 'insert',
                            icon: '/plugins/ckeditor/4.1/jgh/link-copy.png'
                        });
                        window['c{{ $item['key'] }}'].addCommand("CmLinkCopy", {
                            exec: function (edt) {
                                c = 'c{{ $item['key'] }}';
                                $('#ckEditor-linkCopy-cm').click();
                            }
                        });
                        function ckEditorCmAddImage(data) {
                            $(data['result']).each(function (i, item) {
                                window[c].insertElement(CKEDITOR.dom.element.createFromHtml('<img src="' + item['url'] + '" alt=""/>'));
                            })
                        }
                        function ckEditorCmLinkCopy(data) {
                            $('#CmCkEditorCopyInput').remove();
                            $('body').append('<input id="CmCkEditorCopyInput" type="text" value="'+data['result']['url']+'">');
                            copyToClipboard('ckEditorCopyInput');
                        }
                        function copyToClipboard(id) {
                            var copyText = document.getElementById(id);
                            copyText.select();
                            copyText.setSelectionRange(0, 99999); /*For mobile devices*/
                            document.execCommand("copy");
                            iziToast.success({
                                title: '',
                                message: 'لینک با موفقیت کپی شد',
                                position: 'bottomRight',
                                rtl: true,
                            });
                        }
                    </script>
                    <input key="gallery" callback="ckEditorCmAddImage" buttonTitle="ثبت تصویر" class="multiple uploader-open" id="ckEditor-addImage-cm" type="hidden">
                    <input key="links" callback="ckEditorCmLinkCopy" buttonTitle="کپی لینک" class="uploader-open" id="ckEditor-linkCopy-cm" type="hidden">
                </div>

            @endforeach
        </div>
    </div>
@endif
