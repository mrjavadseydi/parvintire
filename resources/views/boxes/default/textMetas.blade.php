@if(isset($postTypeMetas[$key]))
    <div class="box box-warning">
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
                    <textarea name="postTypeMetas[{{ $key }}][{{ $item['key'] }}]" cols="30" rows="10">{{ $v == null ? '' : $v->value }}</textarea>
                </div>
            @endforeach
        </div>
    </div>
@endif
