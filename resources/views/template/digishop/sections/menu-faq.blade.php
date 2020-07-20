<?php
    $posts = \LaraBase\Posts\Models\Post::whereIn('id', explode(',', $ids ?? ''))->get();
?>
@if($posts != null)
    <div class="faq w-100">
        <div id="accordion">
            @foreach($posts as $i => $post)
                <div class="card {{ $i == 0 ? '' : 'collapsed' }} mb-2" data-toggle="collapse" data-target="#i-{{ $i }}" aria-expanded="true" aria-controls="i-{{ $i }}">
                    <div class="card-header">
                        <h6>{{ $post->title }} <i class="fal fa-angle-down float-left"></i></h6>
                    </div>
                    <div id="i-{{ $i }}" class="collapse {{ $i == 0 ? 'show' : '' }}" aria-labelledby="i-{{ $i }}" data-parent="#accordion">
                        <div class="card-body">
                            {!! $post->content !!}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
