<div class="col-lg-4">
    <div class="input-group">
        <label>استان</label>
        <select class="select2" name="provinces[]" multiple="multiple">
            @foreach(\LaraBase\World\models\Province::all() as $record)
                <option
                    {{ (in_array($record->id, (isset($_GET['provinces']) ? $_GET['provinces'] : [])) ? "selected" : "" ) }}
                    value="{{ $record->id }}">{{ $record->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="col-lg-4">
    <div class="input-group">
        <label>شهرستان</label>
        <select class="select2" name="cities[]" multiple="multiple">
            @foreach(\LaraBase\World\models\City::all() as $record)
                <option
                    {{ (in_array($record->id, (isset($_GET['cities']) ? $_GET['cities'] : [])) ? "selected" : "" ) }}
                    value="{{ $record->id }}">{{ $record->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="col-lg-4">
    <div class="input-group">
        <label>شهر</label>
        <select class="select2" name="towns[]" multiple="multiple">
            @foreach(\LaraBase\World\models\Town::all() as $record)
                <option
                    {{ (in_array($record->id, (isset($_GET['towns']) ? $_GET['towns'] : [])) ? "selected" : "" ) }}
                    value="{{ $record->id }}">{{ $record->name }}</option>
            @endforeach
        </select>
    </div>
</div>
