<div class="box box-success">

    <div class="box-header">
        <h3 class="box-title">پدینگ</h3>
        <div class="box-tools">
            <i class="box-tools-icon icon-minus"></i>
        </div>
    </div>
    
    <div class="box-body">
        <?php
        $pr = $pl = $pt = $pb = 0;
        if (isset($values->css)) {
            $colStyles = explode(' ', $values->css->col);
            foreach ($colStyles as $colStyle) {

                if (strpos($colStyle, 'pr') !== false)
                    $pr = str_replace('pr', '', $colStyle);
                else if (strpos($colStyle, 'pl') !== false)
                    $pl = str_replace('pl', '', $colStyle);
                else if (strpos($colStyle, 'pt') !== false)
                    $pt = str_replace('pt', '', $colStyle);
                else if (strpos($colStyle, 'pb') !== false)
                    $pb= str_replace('pb', '', $colStyle);

            }
        }
        ?>

        <div class="row">
                <div class="col-12">
                    <div class="input-group pr tal p7">
                        <label for="">padding-bottom</label>
                        <div id="padding-bottom"></div>
                        <input readonly class="rangeslider" name="css[col][pb]">
                        <script>
                            $( document ).ready(function() {
                                var range = document.getElementById('padding-bottom');
                                noUiSlider.create(range, {
                                    start: {{ $pb }},
                                    step: 1,
                                    range: {
                                        min: 0,
                                        max: 100
                                    }
                                });
                                range.noUiSlider.on('update', function (values, handle) {
                                    $('input[name="css[col][pb]"]').val(parseInt(values));
                                    styles['padding-bottom'] = parseInt(values)+'px';
                                    changeStyles();                                        });
                            });
                        </script>
                    </div>
                </div>

                <div class="col-12">
                    <div class="input-group pr tal p7">
                        <label for="">padding-right</label>
                        <div id="padding-right"></div>
                        <input readonly class="rangeslider" name="css[col][pr]">
                        <script>
                            $( document ).ready(function() {
                                var range = document.getElementById('padding-right');
                                noUiSlider.create(range, {
                                    start: {{ $pr }},
                                    step: 1,
                                    range: {
                                        min: 0,
                                        max: 100
                                    }
                                });
                                range.noUiSlider.on('update', function (values, handle) {
                                    $('input[name="css[col][pr]"]').val(parseInt(values));
                                    styles['padding-right'] = parseInt(values)+'px';
                                    changeStyles();                                        });
                            });
                        </script>
                    </div>
                </div>

                <div class="col-12">
                    <div class="input-group pr tal p7">
                        <label for="">padding-top</label>
                        <div id="padding-top"></div>
                        <input readonly class="rangeslider" name="css[col][pt]">
                        <script>
                            $( document ).ready(function() {
                                var range = document.getElementById('padding-top');
                                noUiSlider.create(range, {
                                    start: {{ $pt }},
                                    step: 1,
                                    range: {
                                        min: 0,
                                        max: 100
                                    }
                                });
                                range.noUiSlider.on('update', function (values, handle) {
                                    $('input[name="css[col][pt]"]').val(parseInt(values));
                                    styles['padding-top'] = parseInt(values)+'px';
                                    changeStyles();                                        });
                            });
                        </script>
                    </div>
                </div>

                <div class="col-12">
                    <div class="input-group pr tal p7">
                        <label for="">padding-left</label>
                        <div id="padding-left"></div>
                        <input readonly class="rangeslider" name="css[col][pl]">
                        <script>
                            $( document ).ready(function() {
                                var range = document.getElementById('padding-left');
                                noUiSlider.create(range, {
                                    start: {{ $pl }},
                                    step: 1,
                                    range: {
                                        min: 0,
                                        max: 100
                                    }
                                });
                                range.noUiSlider.on('update', function (values, handle) {
                                    $('input[name="css[col][pl]"]').val(parseInt(values));
                                    styles['padding-left'] = parseInt(values)+'px';
                                    changeStyles();                                        });
                            });
                        </script>
                    </div>
                </div>

            </div>

    </div>
    
</div>