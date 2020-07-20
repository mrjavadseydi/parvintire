<div class="mt-2 mb-3">
    <?php
    $img = getOptionImage('hoorbookAuthLogo');
    ?>
    <a target="{{ $img['target'] }}" href="{{ $img['href'] }}">
        <img style="max-width: 100%;" src="{{ $img['src'] }}" alt="{{ $img['alt'] }}">
    </a>
</div>
