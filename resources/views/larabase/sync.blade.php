<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>sync</title>
</head>
<body>

<form method="get" action="{{ url('larabase/sync/run') }}">

    <label style="display: block;" for="larabase">
        <input name="larabase" id="larabase" type="checkbox">
        <span>larabase</span>
    </label>

    <label style="display: block;" for="{{ $appName }}">
        <input name="project" id="{{ $appName }}" type="checkbox">
        <input name="projectName" id="{{ $appName }}" value="{{ $appName }}" type="hidden">
        <span>{{ $appName }}</span>
    </label>

    @foreach($themes as $theme)
        <label style="display: block;" for="for-{{ $theme->id }}">
            <input name="themes[{{ str_replace('Theme', '', $theme->key) }}]" id="for-{{ $theme->id }}" value="{{ $theme->value }}" type="checkbox">
            <span>{{ $theme->key }}-{{ $theme->value }}</span>
        </label>
    @endforeach

    <button>submit</button>

</form>

</body>
</html>
