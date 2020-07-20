<div class="input-group mt10">
    <label>وضعیت</label>
    <select class="select2" name="statuses[]" multiple="multiple">
        @foreach(getUserPostStatuses() as $key => $record)
            <option
                {{ (in_array($key, ($_GET['statuses'] ?? [])) ? "selected":"") }}
                value="{{ $key }}">{{ $record['title'] }}
            </option>
        @endforeach
    </select>
</div>
