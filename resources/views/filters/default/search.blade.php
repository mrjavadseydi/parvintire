<div class="input-group mt10">
    <label>جستجو</label>
    <input placeholder="{{ (isset($placeholder) ? $placeholder : '') }}" type="text" name="search" value="{{ (isset($_GET['search']) ? $_GET['search'] : '') }}">
</div>
