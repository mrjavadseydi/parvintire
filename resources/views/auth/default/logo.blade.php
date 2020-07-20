<div class="mt-2 mb-3">
    <?php
    $faranLogo = getOptionImage('defaultAuthLogo');
    ?>
    <a target="{{ $faranLogo['target'] }}" href="{{ $faranLogo['href'] }}">
        <img style="max-width: 100%;" src="{{ $faranLogo['src'] }}" alt="{{ $faranLogo['alt'] }}">
    </a>
</div>
