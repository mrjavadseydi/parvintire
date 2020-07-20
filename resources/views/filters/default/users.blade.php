<div class="input-group mt10">
    <label>نویسنده</label>
    <select class="select2-users w100" name="users[]" multiple="true">
        @foreach ($_GET['users'] ?? [] as $userId)
            @php
            $user = \App\User::where('id', $userId)->first();
            @endphp
            <option selected value="{{ $userId }}">{{ $user->name() }}</option>
        @endforeach
    </select>
</div>
