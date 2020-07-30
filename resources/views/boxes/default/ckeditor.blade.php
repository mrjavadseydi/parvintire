<div class="input-group">
    <label>{{ $box['title'] }}</label>
    <textarea name="{{ $key }}"
        {{ boxAttributes($box) }}
        {{ boxClasses($box, 'ckeditor-' . $key) }}
        {{ boxIds($box, 'ckeditor-' . $key) }}>{{ old($key) ?? $post->$key }}</textarea>
    <script>
        ckEditor = CKEDITOR.replace( '{{ $key }}' ,{
            height: '250px'
        });
        ckEditor.ui.addButton('jghAddImage', {
            label: "افزودن تصویر",
            command: 'addImage',
            toolbar: 'insert',
            icon: '/plugins/ckeditor/4.1/jgh/add-image.png'
        });
        ckEditor.addCommand("addImage", {
            exec: function (edt) {
                $('#ckEditor-addImage').click();
            }
        });
        ckEditor.ui.addButton('jghCopyUrl', {
            label: "کپی لینک",
            command: 'linkCopy',
            toolbar: 'insert',
            icon: '/plugins/ckeditor/4.1/jgh/link-copy.png'
        });
        ckEditor.addCommand("linkCopy", {
            exec: function (edt) {
                $('#ckEditor-linkCopy').click();
            }
        });
        function ckEditorAddImage(data) {
            $(data['result']).each(function (i, item) {
                ckEditor.insertElement(CKEDITOR.dom.element.createFromHtml('<img src="' + item['url'] + '" alt=""/>'));
            })
        }
        function ckEditorLinkCopy(data) {
            $('#ckEditorCopyInput').remove();
            $('body').append('<input id="ckEditorCopyInput" type="text" value="'+data['result']['url']+'">');
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
    <input key="gallery" callback="ckEditorAddImage" buttonTitle="ثبت تصویر" class="multiple uploader-open" id="ckEditor-addImage" type="hidden">
    <input key="links" callback="ckEditorLinkCopy" buttonTitle="کپی لینک" class="uploader-open" id="ckEditor-linkCopy" type="hidden">
</div>
