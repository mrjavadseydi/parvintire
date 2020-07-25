<td>
    @if(isset($edit))
        <a href="{{ $edit }}" title="ویرایش" class="jgh-tooltip ml-1 fa fa-edit text-success mb-0 h5"></a>
    @endif
    @if(isset($viewedAt))
        @if(empty($record->viewed_at))
            <a href="{{ $viewedAt }}" title="مشاهده شد" class="jgh-tooltip fad fa-eye text-info mb-0 h5"></a>
        @endif
    @endif
    @if(isset($delete))
        {{ delete([
            'title' => $delete['title'],
            'action' => $delete['action']
        ]) }}
    @endif
</td>
