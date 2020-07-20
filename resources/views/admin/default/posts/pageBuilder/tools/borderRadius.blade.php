<div class="box box-warning">

    <div class="box-header">
        <h3 class="box-title">رادیوس</h3>
        <div class="box-tools">
            <i class="box-tools-icon icon-minus"></i>
        </div>
    </div>

    <div class="box-body">
        <?php
        $br = 0;
        if (isset($values->css)) {
            $elStyles = explode(' ', $values->css->el);
            foreach ($elStyles as $elStyle) {
                if (strpos($elStyle, 'br') !== false) {
                    $br = str_replace('br', '', $elStyle);
                }
            }
        }
        ?>

        <div class="row">
            <div class="col-12">
                <div class="input-group tal p7">
                    <label for="">border-radius</label>
                    <div id="border-radius"></div>
                    <input readonly class="rangeslider" name="css[el][br]">
                    <script>
                        $( document ).ready(function() {
                            var range = document.getElementById('border-radius');
                            noUiSlider.create(range, {
                                start: {{ $br }},
                                step: 1,
                                range: {
                                    min: 0,
                                    max: 100
                                }
                            });
                            range.noUiSlider.on('update', function (values, handle) {
                                $('input[name="css[el][br]"]').val(parseInt(values));
                                styles['border-radius'] = parseInt(values)+'px';
                                changeStyles();                                        });
                        });
                    </script>
                </div>
            </div>

        </div>
    </div>
</div>