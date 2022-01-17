<div class="box box-purple">

    <div class="box-header">
        <h3 class="box-title">{{ $box['title'] }} ({{ $post->title }})</h3>
        <div class="box-tools">
            <i class="box-tools-icon icon-minus"></i>
        </div>
    </div>

    <div class="box-body specification">
        @php
            $keys = $values = [];
        @endphp
        @foreach ($post->plans() as $item)
            <div id="post-specifications">
                <div class="responsive-table">
                    <table>
                        <thead>
                        <tr class="tar">
                            <th colspan="2" class="w25">{{ $item['title'] }}</th>
                            <th class="w15 tac">وضعیت</th>
                            <th class="w15 tac">ویرایش</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($item['keys'] as $key)
                            <tr>
                                <td>{{ $key['title'] }}</td>
                                <td class="w25">
                                    <select class="w100 plan-value" name="plans[{{ $item['id'] }}][{{ $key['id'] }}][id]">
                                        @foreach ($key['values'] as $value)
                                            <option {{ $value['active'] == '1' ? 'selected' : '' }} value="{{ $value['id'] }}">{{ $value['title'] }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="plans[{{ $item['id'] }}][{{ $key['id'] }}][active]">
                                        <option value="0">غیرفعال</option>
                                        <option {{ $key['active'] == '1' ? 'selected' : '' }} value="1">فعال</option>
                                    </select>
                                </td>
                                <td class="tac">
                                    <a title="ویرایش مقدار" class="value-href btn-icon-success btn-icon icon-mode_edit"></a>
                                    <a title="ویرایش {{ $key['title'] }}" target="_blank" href="{{ route('admin.attribute-keys.edit', ['id' => $key['id']]) }}" class="btn-icon-info btn-icon icon-mode_edit"></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
        @endforeach
    </div>

</div>

<input type="hidden" name="planUrl" value="{{ url('admin/attribute-values') }}">
<script>
    $(document).on('click', '.value-href', function () {
        var valueId = $(this).parent().parent().find('.plan-value').val();
        if (valueId != '') {
            window.open($('input[name=planUrl]').val() + '/' + valueId + '/edit');
        }
    });
</script>
