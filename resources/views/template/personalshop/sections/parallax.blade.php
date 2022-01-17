<?php
$img = getOptionImage($key);
?>
<div class="image-story" style="background-image: url('{{ $img['src'] }}')">
    <div class="hilight">
        <div class="title">
            <h3>{{ $img['alt'] }}</h3>
        </div>
    </div>
</div>
