<div class="uploader-box-body-sidebar">

    <div id="newUpload">

        <div class="d-table">
            <div class="d-table-cell">
                <span class="db mb5">برای بارگذاری، پرونده ها را به اینجا بکشید</span>
                <small class="db mb20">یا</small>
                <label url="{{ url('upload') }}"
                       onAddFiles="uploaderOnAddFiles"
                       onProgress="uploaderOnProgress"
                       onComplete="uploaderOnComplete"
                       class="uploader-click upload-btn">
                    بارگذاری پرونده‌ها
                </label>
            </div>
        </div>

    </div>

</div>
