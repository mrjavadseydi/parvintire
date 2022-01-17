<div class="input-group">
    <label>{{ $box['title'] }}</label>
    <input readonly class="persianDate ltr" type="text" name="{{ $key }}" value="">
</div>

<script>
    $('.persianDate').persianDatepicker({
        format: 'YYYY/MM/DD',
        autoClose: true
    });
</script>
