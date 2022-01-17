<div class="icons" callback="">

    <div class="icons-body">

        <div class="input-group">
            <label for="">جستجو</label>
            <label class="icons-close">بستن</label>
            <input type="text" name="icons-search" value="">
        </div>

        <ul>
            @foreach (getIcons($type) as $icon => $version)
                @if(view()->exists("icons.default.{$icon}"))
                    @include("icons.default.{$icon}")
                @endif
            @endforeach
        </ul>

    </div>

</div>
