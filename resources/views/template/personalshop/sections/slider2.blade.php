<div id="carouselExampleIndicators" class="carousel slide carousel-slider slider-2" data-ride="carousel">
    <div class="bg-slider-2"></div>
    <ol class="carousel-indicators home-2">
        <?php $i = 0;?>
        @foreach($records as $record)
            <li data-target="#carouselExampleIndicators" data-slide-to="{{ $i }}" class="{{ $i == 0 ? 'active' : '' }}"></li>
                <?php $i++;?>
        @endforeach
    </ol>
    <div class="carousel-inner slider-2 position-relative">
        <?php $i = 0;?>
        @foreach($records as $record)
            <a class="carousel-item {{ $i == 0 ? 'active' : '' }}">
                <img class="d-block w-100" src="{{ $record->thumbnail(750, 450) }}" alt="{{ $record->title }}">
            </a>
            <?php $i++;?>
        @endforeach
    </div>
    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>

<script>
    $('.carousel-slider').carousel({
        interval: 4000
    });
</script>
