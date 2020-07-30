<?php
$gallery = \LaraBase\Posts\Models\Post::postType('gallery')->published()->latest()->limit(4)->get();
?>
@if($gallery->count() > 0)
    <div class="gallery">
        <div class="header rounded pt-5">
            @include(includeTemplate('divider.4'), ['title' => 'گالری تصاویر'])
        </div>
        <div class="d-flex margin">
            <div class="d-flex flex-column justify-content-between">
                @foreach($gallery as $item)
                    <figure class="gallery-item" title="{{ $item->title }}" src="{{ $item->thumbnail(1000, 550) }}">
                        <img class="rounded" src="{{ $item->thumbnail(130, 130) }}" alt="{{ $item->title }}">
                    </figure>
                @endforeach
            </div>
            <figure class="mr-3 position-relative">
                <img id="gallery-image" height="100%" width="100%" class="rounded" src="{{ $gallery[0]->thumbnail(1000, 550) }}" alt="{{ $gallery[0]->title }}">
                <figcaption id="gallery-title">{{ $gallery[0]->title }}</figcaption>
            </figure>
        </div>
    </div>

    <script>
        $('.gallery-item').click(function () {
            $('#gallery-title').text($(this).attr('title'));
            $('#gallery-image').attr('src', $(this).attr('src'))
        });
    </script>
@endif
