@if(isset($attachments))
    <div class="uploaded-files">
        @foreach($attachments as $attachment)
            <div style="{{ ($all ? 'display: inline-block !important;' : '') }}" id="{{ $attachment->id }}" thumbnail="{{ $attachment->thumbnail() }}" url="{{ url($attachment->path) }}" path="{{ $attachment->path }}" name="{{ $attachment->title }}" class="uploader-item {{ $attachment->echoIcon() }} type-{{ $attachment->type }} subtype-{{ $attachment->extension }}">
                <img class="select" src="{{ uploaderSelectIcon() }}" alt="selected image">
                <img attachment="{{ $attachment->id }}" class="delete" confirm="{{ image('checked.png', 'uploader') }}" src="{{ uploaderDeleteIcon() }}" alt="delete image">
                <div class="img">
                    <img src="{{ $attachment->thumbnail() }}" alt="{{ $attachment->title }}">
                </div>
                <span style="display: block;" class="upload-title">{{ $attachment->title }}</span>
            </div>
        @endforeach
    </div>
    <div class="uploader-pagination">
        {{ $attachments->render() }}
    </div>
@endif
