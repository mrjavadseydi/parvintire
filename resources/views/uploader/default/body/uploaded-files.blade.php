<div id="uploaded" class="active">

    <div class="uploading-body">

    </div>

    <div class="uploaded-body">
        @include('uploader.default.cards.main')
    </div>

    <input type="hidden" name="uploaderExtensions" value="">

    <style id="uploaderStyle">

    </style>
    <div id="uploader-loading">
        <div class="uploader-loading">
            <img src="{{ uploaderLoading() }}" alt="uploader loading">
        </div>
    </div>

</div>
