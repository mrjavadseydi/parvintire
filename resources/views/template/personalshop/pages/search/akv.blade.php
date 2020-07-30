@if(!empty($akvFilter))
    @foreach($akvFilter as $attr)
        @foreach($attr['keys'] as $item)
            <div class="card mt-2">
                <div class="card-body">
                    @include(includeTemplate('divider.2'), ['title' => "<small>{$item['title']}</small>" ?? ''])
                    <br>
                    @foreach($item['values'] as $item2)
                        <div class="dropdown-filter">
                            <label for="for-{{ $item2['value'] }}"><input class="align-middle ml-2" type="checkbox" name="attributes[]" value="{{ $item2['value'] }}" id="for-{{ $item2['value'] }}">{{ $item2['title'] }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    @endforeach
@endif
