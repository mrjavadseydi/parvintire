<div class="box box-teal">

    <div class="box-header">
        <h3 class="box-title">responive</h3>
        <div class="box-tools">
            <i class="box-tools-icon icon-minus"></i>
        </div>
    </div>

    <div class="box-body">
        <?php
        $cols = [];
        if (isset($values->responsive)) {
            $colParts = explode(' ', $values->responsive);
            foreach ($colParts as $val) {
                if (strpos($val, 'col-xl-') !== false)
                    $cols['colXl'] = str_replace('col-xl-', '', $val);
                else if (strpos($val, 'col-lg-') !== false)
                    $cols['colLg'] = str_replace('col-lg-', '', $val);
                else if (strpos($val, 'col-md-') !== false)
                    $cols['colMd'] = str_replace('col-md-', '', $val);
                else if (strpos($val, 'col-sm-') !== false)
                    $cols['colSm'] = str_replace('col-sm-', '', $val);
                else
                    $cols['col'] = str_replace('col-', '', $val);
            }
        }
        ?>
        <div class="row mb30">

            <div class="col-lg-12">
                <div class="input-group tal">
                    <label for="">col-xl</label>
                    <input name="responsive[col-xl-]" type="text" value="{{ (isset($cols['colXl']) ? $cols['colXl'] : "") }}" class="ltr">
                </div>
            </div>

            <div class="col-lg-12">
                <div class="input-group tal">
                    <label for="">col-lg</label>
                    <input name="responsive[col-lg-]" type="text" value="{{ (isset($cols['colLg']) ? $cols['colLg'] : "") }}" class="ltr">
                </div>
            </div>

            <div class="col-lg-12">
                <div class="input-group tal">
                    <label for="">col-md</label>
                    <input name="responsive[col-md-]" type="text" value="{{ (isset($cols['colMd']) ? $cols['colMd'] : "") }}" class="ltr">
                </div>
            </div>

            <div class="col-lg-12">
                <div class="input-group tal">
                    <label for="">col-sm</label>
                    <input name="responsive[col-sm-]" type="text" value="{{ (isset($cols['colSm']) ? $cols['colSm'] : "") }}" class="ltr">
                </div>
            </div>

            <div class="col-lg-12">
                <div class="input-group tal">
                    <label for="">col</label>
                    <input name="responsive[col-]" type="text" value="{{ (isset($cols['col']) ? $cols['col'] : "") }}" class="ltr">
                </div>
            </div>

            <div class="col-lg-12">
                <div class="input-group tal">
                    <label for="">width</label>
                    <select name="container">
                        <option {{ (isset($values->container) ? (selected($values->container, 'container')) : "") }} value="container">container</option>
                        <option {{ (isset($values->container) ? (selected($values->container, 'fullWidth')) : "") }} value="fullWidth">fullWidth</option>
                    </select>
                </div>
            </div>

        </div>
    </div>

    <div class="box-footer">

    </div>

</div>