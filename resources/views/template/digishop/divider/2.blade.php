<div class="divider-2 d-flex justify-content-between">
    <div class="d-flex align-items-center">
       <i class="fa fa-diamond align-middle"></i>
       <h4>{!! $title ?? '' !!}</h4>
    </div>
    <a {{ ($href ?? null) == null ? '' : 'href="'.$href.'"' }}>{{ $secondTitle ?? '' }}</a>
</div>
