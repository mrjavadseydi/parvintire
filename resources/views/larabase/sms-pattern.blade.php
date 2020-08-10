<?php
    function renderSmsMessage($message) {
        preg_match_all('/\$(.*?)\$/', $message, $variablesMatch);
        foreach ($variablesMatch[1] as $variable) {
            $message = str_replace("$".$variable."$", getOption($variable), $message);
        }
        return $message;
    }
?>
<form action="{{ url('larabase/sms/sync') }}" method="post">
    @foreach($patterns->disablePatterns as $key => $pattern)
        <h1>{{ $key }}</h1>
        <textarea name="patterns[{{ $key }}]" id="" cols="30" rows="10">{{ old('message') ?? renderSmsMessage($pattern->message) }}</textarea>
    @endforeach
    <br>
    <br>
    <button>ذخیره</button>
</form>
<style>
    textarea {
        direction: rtl;
    }
</style>
