<?php
extract($post->products()); // $products - $attributes - $files

$productType = null;
$currencyCode = null;
$shippingId = null;
$taxId = null;

if ($products->count() > 0) {
    $product = $products[0]; // first product
    $productType = $product->type;
    $currencyCode = $product->currency;
    $shippingId = $product->shipping_id;
    $taxId = $product->tax_id;
}

if (in_array('postalProduct', $postBoxes)) {
    $productType = 'postal';
} else if (in_array('downloadProduct', $postBoxes)) {
    $productType = 'download';
} else if (in_array('virtualProduct', $postBoxes)) {
    $productType = 'virtual';
}

$amountTitle = 'موجودی';
if (in_array('plan', $postBoxes)) {
    $amountTitle = 'ساعت';
}
?>

<div class="box box-success">

    <div class="box-header">
        <h3 class="box-title">{{ $box['title'] }}</h3>
        <div class="box-tools">
            <i class="box-tools-icon icon-minus"></i>
        </div>
    </div>

    <div class="box-body">

        <div class="row">

            <div class="col-12">
                <div class="alert alert-info">
                    <p>لطفا مبالغ را به <b>ریال</b> وارد کنید</p>
                </div>
            </div>

            <div class="col-4">
                <div class="input-group">
                    <label>محصول {{ config("product.types.{$productType}.title") }}</label>
                    <select id="product_type">

                        @if($productAttributes != null)
                            <option value="variable">متغیر</option>
                        @else
                            @if($products->count() == 0)
                                @if($productType != 'virtual')
                                    <option value="simple">ساده</option>
                                @endif
                                @if($productType != 'download')
                                    <option value="variable">متغیر</option>
                                @endif
                            @endif

                            @if($products->count() == 1)
                                @if($productType != 'virtual')
                                    <option value="simple">ساده</option>
                                @endif
                            @endif

                            @if($products->count() > 1)
                                @if($productType != 'download')
                                    <option value="variable">متغیر</option>
                                @endif
                            @endif
                        @endif

                    </select>
                </div>
            </div>

            <div class="col-4">
                <div class="input-group">
                    <label>حمل و نقل</label>
                    <select name="product_shipping_id">
                        @foreach (shipping() as $shipping)
                            <option {{ ($shipping->id == $shippingId ? 'selected' : '') }} value="{{ $shipping->id }}">{{ $shipping->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-4">
                <div class="input-group">
                    <label>مالیات</label>
                    <select name="product_tax_id">
                        <option value="">هیچ کدام</option>
                        @foreach (taxes() as $tax)
                            <option {{ ($tax->id == $taxId ? 'selected' : '') }} value="{{ $tax->id }}">{{ $tax->title . ' ' . $tax->percent }}%</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="{{ (count($productAttributes) > 0 ? '' : ($productType == 'virtual' ? '' : 'dn')) }} variable col-12 mt20">

                <div class="input-group">
                    <label>ویژگی ها</label>

                    <script id="productsTemplate" type="text/template7">
                        <div id="@{{ id }}" class="product-item input-group">
                            <label class="product-item-title">@{{ title }}</label> <small class="delete-product-item" style="color: red; cursor: pointer;">@{{ deleteTitle }}</small>
                            <div class="row">

                                <div class="col-4 pr5">
                                    <div class="input-group">
                                        <label>عنوان</label>
                                        <input name="" value="{{ $post->title }}@{{ title2 }}" class="title-name" type="text">
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="input-group">
                                        <label>قیمت خرید</label>
                                        <input name="" class="ltr purchase-price-name money-mask" type="text">
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="input-group">
                                        <label>قیمت فروش</label>
                                        <input name="" class="ltr price-name money-mask" type="text">
                                    </div>
                                </div>

                                <div class="col-2 pl0">
                                    <div class="input-group">
                                        <label>{{ $amountTitle }}</label>
                                        <input class="ltr stock" type="text">
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="input-group">
                                        <label>قیمت ویژه</label>
                                        <input class="ltr special-price-name money-mask" type="text">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="input-group">
                                        <label>از تاریخ</label>
                                        <input class="ltr start-date datetime-mask" type="text">
                                    </div>
                                </div>
                                <div class="col-4 pl0">
                                    <div class="input-group">
                                        <label>تا تاریخ</label>
                                        <input class="ltr end-date datetime-mask" type="text">
                                    </div>
                                </div>
                            </div>
                            <hr>
                        </div>
                    </script>

                    <div class="table-responsive mt5">
                        <table id="productAttributeTable">
                            <thead>
                                <tr>
                                    <th style="width: 100px">ویژگی</th>
                                    <th>مقادیر</th>
                                    <th style="width: 50px">عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($attributes as $attributeId => $attribute)
                                <tr class="added-product-attribute-{{ $attribute['id'] }} tac">
                                    <td>{{ $attribute['title'] }}</td>
                                    <td>
                                        <select title="{{ $attribute['title'] }}" attributeId="{{ $attributeId }}">
                                            @foreach($attribute['keys'] as $key)
                                                <option value="{{ $key['id'] }}">{{ $key['title'] }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="tac">
                                        <a class="delete-product-attribute btn-icon btn-icon-danger icon-delete toolip" title="حذف"></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="tal mt2">
                            <span class="btn btn-success add-product">افزودن محصول</span>
                        </div>
                    </div>

                </div>

            </div>

            <div class="products col-12 mt20">

                <?php $i = 0; ?>
                @foreach($products as $product)

                    <?php
                        $j = 0;
                    ?>

                    <div id="product-{{ implode('-', $productAttributes[$product->product_id] ?? []) }}" class="product-item input-group">
                        @foreach ($productAttributes[$product->product_id] ?? [] as $attrId => $kId)
                            <input type="hidden" class="attributes attributeKey" index="{{ $j }}" name="products[{{ $i }}][attributes][{{ $j }}][key]" value="{{ $attrId }}">
                            <input type="hidden" class="attributes attributeValue" index="{{ $j }}" name="products[{{ $i }}][attributes][{{ $j }}][value]" value="{{ $kId }}">
                            <?php $j++; ?>
                        @endforeach
                        <div class="row">
                            <div class="col-4 pr5">
                                <div class="input-group">
                                    <label>عنوان</label>
                                    <input name="products[{{ $i }}][title]" value="{{ $product->title }}" class="title-name" type="text">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="input-group">
                                    <label>قیمت خرید</label>
                                    <input name="products[{{ $i }}][purchase_price]" value="{{ $product->purchase_price }}" class="ltr purchase-price-name money-mask" type="text">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="input-group">
                                    <label>قیمت فروش</label>
                                    <input name="products[{{ $i }}][price]" value="{{ $product->price }}" class="ltr price-name money-mask" type="text">
                                </div>
                            </div>
                            <div class="col-2 pl0">
                                <div class="input-group">
                                    <label>{{ $amountTitle }}</label>
                                    <input name="products[{{ $i }}][stock]" value="{{ $product->stock }}" class="ltr stock" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="input-group">
                                    <label>قیمت ویژه</label>
                                    <input name="products[{{ $i }}][special_price]" value="{{ $product->special_price }}" class="ltr special-price-name money-mask" type="text">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="input-group">
                                    <label>از تاریخ</label>
                                    <input name="products[{{ $i }}][start_date]" value="{{ (!empty($product->start_date) ? toEnglish(jDateTime('Y-m-d H:i:s', strtotime($product->start_date))) : '') }}" class="ltr start-date datetime-mask" type="text">
                                </div>
                            </div>
                            <div class="col-4 pl0">
                                <div class="input-group">
                                    <label>تا تاریخ</label>
                                    <input name="products[{{ $i }}][end_date]" value="{{ (!empty($product->end_date) ? toEnglish(jDateTime('Y-m-d H:i:s', strtotime($product->end_date))) : '') }}" class="ltr end-date datetime-mask" type="text">
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>

                    <?php $i++; ?>

                @endforeach

            </div>

        </div>

        <div class="download {{ ($productType == 'download' ? '' : 'dn') }} input-group mt20">
            <span callback="productFiles" classes="all" uploadIn="2" class="multiple btn btn-info uploader-open">افزودن فایل</span>

            <div class="table-responsive mt10">
                <table>
                    <thead>
                        <tr>
                            <th>پوستر</th>
                            <th>عنوان</th>
                            <th>نوع</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>

                    <script id="productFilesTemplate" type="text/template7">
                        <tr>
                            <td>
                                <img width="100px" src="@{{ poster }}" alt="poster">
                            </td>
                            <td>@{{ title }}</td>
                            <td style="width: 165px">
                                <select class="typeName" name="">
                                    <option value="free">رایگان</option>
                                    <option value="vip">اشتراک</option>
                                    <option value="cash">نقدی</option>
                                </select>
                            </td>
                            <td style="width: 105px">
                                <select class="activeName" name="">
                                    <option value="1">فعال</option>
                                    <option value="0">غیرفعال</option>
                                    <option value="2">حذف</option>
                                </select>
                            </td>
                            <td>
                                <a target="_blank" href="{{ url('admin/attachments') }}/@{{ attachmentId }}/edit" class="btn-icon btn-icon-success icon-mode_edit"></a>
                                <a class="btn-icon btn-icon-danger product-file-delete icon-delete"></a>
                            </td>
                            <input type="hidden" name="" value="@{{ attachmentId }}" class="attachmentId">
                        </tr>
                    </script>

                    <tbody class="product-files tac">
                        @foreach ($files as $i => $file)
                            <tr>
                                <td>
                                    <img width="100px" src="{{ $file->attachment->poster(true) }}" alt="poster">
                                </td>
                                <td>{{ $file->attachment->title }}</td>
                                <td style="width: 165px">
                                    <select class="typeName" name="productFiles[{{ $i }}][type]">
                                        <option {{ ($file->type == 'free' ? 'selected' : '') }} value="free">رایگان</option>
                                        <option {{ ($file->type == 'vip' ? 'selected' : '') }} value="vip">اشتراک</option>
                                        <option {{ ($file->type == 'cash' ? 'selected' : '') }} value="cash">نقدی</option>
                                    </select>
                                </td>
                                <td style="width: 105px;">
                                    <select class="activeName" name="productFiles[{{ $i }}][active]">
                                        <option {{ ($file->active == '1' ? 'selected' : '') }} value="1">فعال</option>
                                        <option {{ ($file->active == '0' ? 'selected' : '') }} value="0">غیرفعال</option>
                                        <option value="2">حذف</option>
                                    </select>
                                </td>
                                <td>
                                    <a target="_blank" href="{{ route('admin.attachments.edit', ['id' => $file->attachment_id]) }}" class="btn-icon btn-icon-success icon-mode_edit"></a>
                                </td>
                                <input type="hidden" name="productFiles[{{ $i }}][attachment_id]" value="{{ $file->attachment_id }}" class="attachmentId">
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

        </div>

        <div class="input-group mt20">
            <label>محصولات مرتبط</label>
            <select name="connected_products" id="select2-products" multiple>

            </select>
        </div>

    </div>

</div>

<script>

    <?php
        if ($products->count() == 0) {
            if ($productType != 'virtual') {
            ?>
            $(document).ready(function () {
                simpleProduct();
            });
            <?php
            }
        }
    ?>

    inputMask();

    $(document).on('change', '#product_type', function () {

        if ($('select[name="product_type"]').val() == 'download') {
            $(this).val('simple');
            return;
        }

        $('.products').html('');
        $('.simple, .variable').hide();
        var cls = '.' + $(this).val();
        $(cls).fadeToggle();
        if (cls == '.simple') {
            simpleProduct();
        }

    });

    $(document).on('change', 'select[name="product_type"]', function () {
        $('.postal, .download, .virtual').hide();
        var cls = '.' + $(this).val();

        if (cls == '.download') {
            $('#product_type').val('simple');
            $('.simple, .variable').hide();
            $('.products').html('');
            simpleProduct();
        }

        $(cls).fadeToggle();

    });

    $('.productAttributesSelect2').select2({
        dir: 'rtl',
    });

    $(document).on('click', '.delete-product-attribute', function () {
        $(this).parent().parent().remove();
    });

    $(document).on('click', '.delete-product-item', function () {
        $(this).parent().remove();
    });

    $(document).on('click', '.add-product', function () {

        var template = $('#productsTemplate').html();
        var compiledTemplate = Template7.compile(template);

        var ids = [];
        var title = '';
        var title2 = '';
        var attributes = [];

        $('#productAttributeTable tbody select').each(function (i, key) {

            var keyId = $(key).attr('attributeId');
            var keyTitle = $(key).attr('title');
            var valueId = $(key).val();
            var valueTitle = $('select[attributeId='+keyId+']').find('option[value='+valueId+']').text();

            title = title + ' ' + keyTitle + ':' + valueTitle;
            title2 = title2 + ' ' + valueTitle;
            ids[i] = valueId;

            attributes[i] = [];
            attributes[i]['keyId'] = keyId;
            attributes[i]['keyTitle'] = keyTitle;
            attributes[i]['valueId'] = valueId;
            attributes[i]['valueTitle'] = valueTitle;

        });

        var id = 'product-' + ids.join('-');

        if ($('#' + id).length == 0) {

            var context = {
                id: id,
                title: '',
                title2: title2,
                deleteTitle: "حذف",
            }

            $('.products').prepend(compiledTemplate(context));

            $(attributes).each(function (i, item) {
                $('#' + id).append('<input type="hidden" class="attributes attributeKey" index="'+i+'" name="" value="'+item.keyId+'">');
                $('#' + id).append('<input type="hidden" class="attributes attributeValue" index="'+i+'" name="" value="'+item.valueId+'">');
            });

            sortProducts();

        }

    });

    $( function() {
        $( ".products" ).sortable({
            stop: function( event, ui ) {
                sortProducts();
            }
        });
        $( ".product-item" ).disableSelection();
    } );

    $(document).ready(function () {
        $('#select2-products').select2({
            dir: "rtl",
            ajax: {
                url: "{{ url('api/product/search') }}",
                data: function (params) {
                    return params;
                },
                processResults: function (data) {
                    return {
                        results: data.items
                    };
                }
            }
        });
    });

    $( function() {
        $( ".product-files" ).sortable({
            stop: function( event, ui ) {
                sortProductFiles();
            }
        });
        $( ".product-files tr" ).disableSelection();
    } );

    $(document).on('click', '.product-file-delete', function () {
        $(this).parent().parent().remove();
    });

    function productFiles(data) {
        if (data.status == 'success') {

            var template = $('#productFilesTemplate').html();
            var compiledTemplate = Template7.compile(template);

            $(data.result).each(function (i, item) {

                var context = {
                    attachmentId: item.id,
                    poster: item.thumbnail,
                    title: item.name,
                }

                $('.product-files').append(compiledTemplate(context));

            });

            sortProductFiles();

        }
    }

    function sortProductFiles() {
        $('.product-files tr').each(function (i, item) {
            $(item).find('.typeName').attr('name', 'productFiles[' + i + '][type]');
            $(item).find('.activeName').attr('name', 'productFiles[' + i + '][active]');
            $(item).find('.attachmentId').attr('name', 'productFiles[' + i + '][attachment_id]');
        })
    }

    function inputMask() {
        $('.money-mask').inputmask({
            alias: 'decimal',
            groupSeparator: ',',
            autoGroup: true,
            rightAlign: false
        });
        $('.datetime-mask').inputmask({
            mask: "9999/99/99 99:99:99",
            placeholder: "mm/dd/yyyy hh:mm:ss",
            alias: "datetime",
            hourFormat: "12"
        });
    }

    function sortProducts() {

        $('.products .product-item').each(function (i, item) {
            $(item).find('.title-name').attr('name', 'products['+i+'][title]');
            $(item).find('.price-name').attr('name', 'products['+i+'][price]');
            $(item).find('.purchase-price-name').attr('name', 'products['+i+'][purchase_price]');
            $(item).find('.special-price-name').attr('name', 'products['+i+'][special_price]');
            $(item).find('.stock').attr('name', 'products['+i+'][stock]');

            var startDate = $(item).find('.start-date');
            var endDate = $(item).find('.end-date');
            startDate.attr('name', 'products['+i+'][start_date]').attr('date-value', startDate.val());
            endDate.attr('name', 'products['+i+'][end_date]').attr('date-value', endDate.val());

            $(item).find('.attributes').each(function (i2, item2) {
                var index = $(item2).attr('index');

                var cls = 'key';
                if ($(item2).hasClass('attributeValue'))
                    cls = 'value';

                $(item2).attr('name', 'products['+i+'][attributes]['+index+']['+cls+']');
            });
        });

        inputMask();

        $('.datetime-mask').each(function () {
            $(this).val($(this).attr('date-value'))
        });

    }

    function simpleProduct() {
        var template = $('#productsTemplate').html();
        var compiledTemplate = Template7.compile(template);
        $('.products').append(compiledTemplate({}));
        sortProducts();
    }

</script>

<style>
    .select2-container {
        width: 100% !important;
    }

    .product-item-title {
        color: #003aa5;
    }

    .product-item {
        background: white;
    }
</style>
