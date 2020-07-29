<?php
    $strToTime = strtotime($post->created_at);
?>
<div class="product-card-1 blog-card-1 position-relative">
    <figure class="text-center">
        <img width="100%" src="{{ $post->thumbnail(350, 250) }}" alt="{{ $post->title }}">
        <a href="{{ $post->href() }}">
            <figcaption class="p-3 iransansMedium">{{ $post->title }}</figcaption>
        </a>
    </figure>
    <div class="d-flex justify-content-between align-items-center p-3 iransansFa">
       <div class="d-flex">
           <div class="pl-2 h4 m-0 iransansMediumFa border-left d-flex align-items-center">
               <span class="price">{{ jDateTime('d', $strToTime) }}</span>
           </div>
           <div class="pr-2 d-flex flex-column">
               <span class="iransansMediumFa text-muted">{{ jDateTime('F', $strToTime) }}</span>
               <span class="iransansMediumFa text-muted small">{{ jDateTime('Y', $strToTime) }}</span>
           </div>
       </div>
        <div class="percent">
            <label class="text-muted">{{ $post->rateByLikes() }}<i class="star fa fa-star d-inline-block mr-1"></i></label>
        </div>
    </div>
</div>
