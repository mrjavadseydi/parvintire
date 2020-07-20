<?php
    $languages = \LaraBase\Posts\Models\Language::all();
?>
@if($languages->count() > 0)
    <form method="get" action="{{ $action }}">
        <div style="border-radius: 5px; border: 1px solid #f36e00; margin-bottom: 10px; padding: 5px;">
            <p style="display: inline-block;display: inline-block;color: #f36e00;"> ثبت ترجمه به زبان </p>
            <select style="background: white; display: inline-block;" name="lang">
                @foreach($languages as $lang)
                    <option value="{{ $lang->lang }}">{{ $lang->title }}</option>
                @endforeach
            </select>
            <button class="btn-lg btn-orange">شروع</button>
        </div>
    </form>
@endif
