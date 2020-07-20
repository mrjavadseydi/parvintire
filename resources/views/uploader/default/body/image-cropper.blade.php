<div id="image-cropper">

    <div id="parent-image-crop" style="max-width: 640px; max-height: 400px; margin: auto; overflow: hidden">
        <img id="image-crop" src="{{ image('auth-box.jpg', 'admin') }}" alt="image copper">
    </div>

</div>

<input type="hidden" name="imageCropperUrl" value="{{ route('image-cropper') }}">
<input type="hidden" name="imageCropperToken" value="{{ csrf_token() }}">
<form onSuccess="imageCropperOnSuccess" onError="imageCropperOnError" id="image-cropper-form" action="" class="ajaxForm">
    <input type="hidden" name="attachmentId" value="">
    <input type="hidden" name="x" value="">
    <input type="hidden" name="y" value="">
    <input type="hidden" name="cropperWidth" value="">
    <input type="hidden" name="cropperHeight" value="">
    <input type="hidden" name="width" value="">
    <input type="hidden" name="height" value="">
    <input type="hidden" name="rotate" value="">
</form>

<style>
    #parent-image-crop {
        direction: ltr;
        text-align: center;
    }

    #parent-image-crop * {
        direction: ltr;
    }

    #parent-image-crop img {
    }
</style>
