<div class="card">
    <div class="card-body">
        @include(includeTemplate('divider.2'), ['title' => "<small>{$title}</small>" ?? ''])
        <br>
        @foreach(treeView(\LaraBase\Categories\Models\Category::where('post_type', $postType)->get()) as $i => $item)
            <div class="dropdown-filter {{ $i == 0 ? 'active' : '' }}">
                <h5>{{ $item['title'] }}<i class="fal {{ $i == 0 ? 'fa-minus' : 'fa-plus' }}"></i></h5>
                @if(isset($item['list']))
                    <ul>
                        @foreach($item['list'] as $item2)
                            <li>
                                <label for="cat-{{ $item2['id'] }}">
                                    <input type="checkbox" name="categories[]" value="{{ $item2['id'] }}" id="cat-{{ $item2['id'] }}">
                                    {{ $item2['title'] }}
                                </label>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endforeach
    </div>
</div>
<script>
    $(document).on('click', '.dropdown-filter', function () {
        $(this).parent().find('.dropdown-filter').removeClass('active');
        $(this).parent().find('.dropdown-filter h5 i').removeClass('fa-minus').addClass('fa-plus');
        $(this).addClass('active');
        $(this).find('h5 i').addClass('fa-minus').removeClass('fa-plus');
    });
</script>
