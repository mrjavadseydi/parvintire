<div class="box box-warning">

    <div class="box-header">
        <h3 class="box-title">{{ $box['title'] }}</h3>
        <div class="box-tools">
            <i class="box-tools-icon icon-minus"></i>
        </div>
    </div>

    <div class="box-body" style="max-height: 400px; overflow: auto;">

        <ul class="checkbox plr15 categories">
            <?php echo checkbox(old('categories') ?? $postCategories, $categoryList);?>
        </ul>

    </div>

</div>
