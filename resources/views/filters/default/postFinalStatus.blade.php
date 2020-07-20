<div class="input-group mt10">
    <label>وضعیت نهایی</label>
    <select class="select2" name="finalStatuses[]" multiple="multiple">
        @foreach(getUserPostStatuses() as $key => $record)
            <option
                {{ (in_array($key, ($_GET['finalStatuses'] ?? [])) ? "selected":"") }}
                value="{{ $key }}">{{ $record['title'] }}
            </option>
        @endforeach
    </select>
</div>
