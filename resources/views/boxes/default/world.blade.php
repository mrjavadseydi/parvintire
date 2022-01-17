<div class="{{ (isset($divClass) ? $divClass : 'col-md-6') }} col-12">
    <div class="input-group">
        <label for="">استان</label>
        <select callback="{{ (isset($provinceCallback) ? $provinceCallback : "") }}" id="world-province-select" class="{{ (isset($selectClass) ? $selectClass : 'world-select2') }}" name="{{ (isset($provinceName) ? $provinceName : (isset($withotId) ? 'province' : 'province_id')) }}">
            <option value="">انتخاب کنید</option>
            @foreach(\LaraBase\World\models\Province::all() as $record)
                <option
                    latitude="{{$record->latitude}}" longitude="{{ $record->longitude }}"
                    {{ (isset($provinceId) ? selected($record->id, $provinceId) : selected($record->id, old('province_id'))) }}
                    value="{{ $record->id }}">
                    {{ $record->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>

@php
    if (!isset($provinceId)) {
        if (!empty(old('province_id'))) {
            $provinceId = old('province_id');
        }
    }

    $cities = [];
    if (isset($provinceId))
        $cities = \LaraBase\World\models\City::where('province_id', $provinceId)->get();

    if (!isset($cityId)) {
        if (!empty(old('city_id'))) {
            $cityId = old('city_id');
        }
    }

    $towns = [];
    if (isset($cityId))
        $towns = \LaraBase\World\models\Town::where('city_id', $cityId)->get();

@endphp

<div class="{{ (isset($divClass) ? $divClass : 'col-md-6') }} col-12">
    <div class="input-group">
        <label for="">شهرستان</label>
        <select callback="{{ (isset($cityCallback) ? $cityCallback : "") }}" id="world-city-select" class="{{ (isset($selectClass) ? $selectClass : 'world-select2') }}" name="{{ (isset($cityName) ? $cityName : (isset($withotId) ? 'city' : 'city_id')) }}">
            <option value="">انتخاب کنید</option>
            @foreach ($cities as $record)
                <option
                        latitude="{{$record->latitude}}" longitude="{{ $record->longitude }}"
                        {{ (isset($cityId) ? selected($record->id, $cityId) : selected($record->id, old('city_id'))) }}
                        value="{{ $record->id }}">{{ $record->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="{{ (isset($divClass) ? $divClass : 'col-md-6') }} col-12">
    <div class="input-group">
        <label for="">شهر</label>
        <select callback="{{ (isset($townCallback) ? $townCallback : "") }}" id="world-town-select" class="{{ (isset($selectClass) ? $selectClass : 'world-select2') }}" name="{{ (isset($townName) ? $townName : (isset($withotId) ? 'town' : 'town_id')) }}">
            <option value="">انتخاب کنید</option>
            @foreach ($towns as $record)
                <option
                        latitude="{{$record->latitude}}" longitude="{{ $record->longitude }}"
                        {{ (isset($townId) ? selected($record->id, $townId) : selected($record->id, old('town_id'))) }}
                        value="{{ $record->id }}">{{ $record->name }}</option>
            @endforeach
        </select>
    </div>
</div>

@if(isset($map))
    <input type="hidden" name="mapLocation" value="true">
@endif
