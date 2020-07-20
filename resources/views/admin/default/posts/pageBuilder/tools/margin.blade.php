<div class="box box-pink">

    <div class="box-header">
        <h3 class="box-title">مارجین</h3>
        <div class="box-tools">
            <i class="box-tools-icon icon-minus"></i>
        </div>
    </div>

    <div class="box-body">
        <?php
        $mr = $ml = $mt = $mb = 0;
        if (isset($values->css)) {
            $colStyles = explode(' ', $values->css->col);
            foreach ($colStyles as $colStyle) {

                if (strpos($colStyle, 'mr') !== false)
                    $mr = str_replace('mr', '', $colStyle);
                else if (strpos($colStyle, 'ml') !== false)
                    $ml = str_replace('ml', '', $colStyle);
                else if (strpos($colStyle, 'mt') !== false)
                    $mt = str_replace('mt', '', $colStyle);
                else if (strpos($colStyle, 'mb') !== false)
                    $mb= str_replace('mb', '', $colStyle);

            }
        }
        ?>

        <div>

            <div class="row">

                <div class="col-12">
                    <div class="input-group pr tal p7">
                        <label for="">margin-bottom</label>
                        <div id="margin-bottom"></div>
                        <input readonly class="rangeslider" name="css[col][mb]">
                        <script>
                            $( document ).ready(function() {
                                var range = document.getElementById('margin-bottom');
                                noUiSlider.create(range, {
                                    start: {{ $mb }},
                                    step: 1,
                                    range: {
                                        min: 0,
                                        max: 100
                                    }
                                });
                                range.noUiSlider.on('update', function (values, handle) {
                                    $('input[name="css[col][mb]"]').val(parseInt(values));
                                    styles['margin-bottom'] = parseInt(values)+'px';
                                    changeStyles();                                        });
                            });
                        </script>
                    </div>
                </div>

                <div class="col-12">
                    <div class="input-group pr tal p7">
                        <label for="">margin-right</label>
                        <div id="margin-right"></div>
                        <input readonly class="rangeslider" name="css[col][mr]">
                        <script>
                            $( document ).ready(function() {
                                var range = document.getElementById('margin-right');
                                noUiSlider.create(range, {
                                    start: {{ $mr }},
                                    step: 1,
                                    range: {
                                        min: 0,
                                        max: 100
                                    }
                                });
                                range.noUiSlider.on('update', function (values, handle) {
                                    $('input[name="css[col][mr]"]').val(parseInt(values));
                                    styles['margin-right'] = parseInt(values)+'px';
                                    changeStyles();                                        });
                            });
                        </script>
                    </div>
                </div>

                <div class="col-12">
                    <div class="input-group pr tal p7">
                        <label for="">margin-top</label>
                        <div id="margin-top"></div>
                        <input readonly class="rangeslider" name="css[col][mt]">
                        <script>
                            $( document ).ready(function() {
                                var range = document.getElementById('margin-top');
                                noUiSlider.create(range, {
                                    start: {{ $mt }},
                                    step: 1,
                                    range: {
                                        min: 0,
                                        max: 100
                                    }
                                });
                                range.noUiSlider.on('update', function (values, handle) {
                                    $('input[name="css[col][mt]"]').val(parseInt(values));
                                    styles['margin-top'] = parseInt(values)+'px';
                                    changeStyles();                                        });
                            });
                        </script>
                    </div>
                </div>

                <div class="col-12">
                    <div class="input-group pr tal p7">
                        <label for="">margin-left</label>
                        <div id="margin-left"></div>
                        <input readonly class="rangeslider" name="css[col][ml]">
                        <script>
                            $( document ).ready(function() {
                                var range = document.getElementById('margin-left');
                                noUiSlider.create(range, {
                                    start: {{ $ml }},
                                    step: 1,
                                    range: {
                                        min: 0,
                                        max: 100
                                    }
                                });
                                range.noUiSlider.on('update', function (values, handle) {
                                    $('input[name="css[col][ml]"]').val(parseInt(values));
                                    styles['margin-left'] = parseInt(values)+'px';
                                    changeStyles();                                        });
                            });
                        </script>
                    </div>
                </div>

            </div>

        </div>
    </div>

</div>