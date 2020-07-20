<div class="box box-danger">

    <div class="box-header">
        <h3 class="box-title">{{ $box['title'] }}</h3>
        <div class="box-tools">
            <i class="box-tools-icon icon-minus"></i>
        </div>
    </div>

    <div class="box-body specification">

        <script id="optionTemplate" type="text/template7">
            <option value="@{{ value }}">@{{ title }}</option>
        </script>

        <script id="postSpecificationKeyTemplate" type="text/template7">
            <tr class="added-specification-key-@{{ id }}">
                <td class="tac">@{{ title }}</td>
                <td>
                    <select class="specificationsSelect2 w100" name="@{{ select2name }}" multiple="multiple">
                    </select>
                </td>
                <td class="tac">
                    <a class="select-all btn-icon btn-icon-success icon-done_all toolip" title="انتخاب همه"></a>
                    <a class="di-select-all btn-icon btn-icon-primary icon-indeterminate_check_box toolip" title="انتخاب هیچکدام"></a>
                    <a class="delete-specification-key-values btn-icon btn-icon-danger icon-delete toolip" title="حذف"></a>
                </td>
            </tr>
        </script>

        @foreach ($post->attributes() as $item)
            <div id="post-specifications">
                <div class="added-specification-{{ $item['id'] }} responsive-table">
                    <table>
                        <thead>
                            <tr>
                                <th colspan="2" class="tar">{{ $item['title'] }}</th>
                                <td class="tac">
                                    <a class="delete-attribute btn-icon btn-icon-danger icon-delete toolip" title="حذف"></a>
                                </td>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($item['keys'] as $key)
                            <tr class="added-specification-key-{{ $key['id'] }}">
                                <td class="w25 tac">{{ $key['title'] }}</td>
                                <td>
                                    <select style="width: 100% !important;" class="specificationsSelect2" name="attributes[{{ $item['id'] }}][{{ $key['id'] }}][]" multiple="multiple">
                                        @foreach ($key['values'] as $value)
                                            <option {{ $value['active'] > 0 ? 'selected' : '' }} value="{{ $value['id'] }}">{{ $value['title'] }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="tac w25">
                                    <a href="{{ route('admin.attribute-keys.edit', ['id' => $key['id']]) }}" target="_blank" class="btn-icon btn-icon-success icon-mode_edit toolip" title="ویرایش"></a>
                                    <a class="select-all btn-icon btn-icon-purple icon-done_all toolip" title="انتخاب همه"></a>
                                    <a class="di-select-all btn-icon btn-icon-primary icon-indeterminate_check_box toolip" title="انتخاب هیچکدام"></a>
                                    <a class="delete-specification-key-values btn-icon btn-icon-danger icon-delete toolip" title="حذف"></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach

    </div>

{{--    <div class="box-footer tal">--}}
{{--        <select name="postSpecifications" id="postSpecifications" class="w40">--}}
{{--            <option value="0">انتخاب کنید</option>--}}
{{--            @foreach (\LaraBase\Attributes\Models\Attribute::where('type', 'post')->get() as $attribute)--}}
{{--                <option value="{{ $attribute->id }}">{{ $attribute->title }}</option>--}}
{{--            @endforeach--}}
{{--        </select>--}}
{{--        <span class="add-attriubte btn-lg btn-success btn-loading" loading="لطفا صبر کنید...">افزودن</span>--}}
{{--    </div>--}}

</div>

<script>

    $(document).ready(function () {
        $('.specificationsSelect2').select2({
            dir: "rtl",
            closeOnSelect: false,
        });
    });

    $(document).on('click', '.add-attribubte', function () {

        var specificationId = $('#postSpecifications').val();

        if (specificationId == 0) {
            swal({
                'title': 'ابتدا یک مشخصه انتخاب کنید',
                icon: 'warning',
                button: 'تایید'
            });
            return;
        }

        if ($('.added-specification-' + specificationId).length > 0) {
            swal({
                'title': 'این مشخصه قبلا اضافه شده است',
                icon: 'warning',
                button: 'تایید'
            });
            return;
        }

        $.ajax({
            url: '{{ url('api/attributes/keys') }}' + '/' + specificationId,
            type: 'GET',
            success: function (response, status) {
                if (response.status === 'success') {

                    var template = $('#postSpecificationTemplate').html();
                    var compiledTemplate = Template7.compile(template);
                    var context = {
                        specificationTitle:     response.attribute.title,
                        specificationId:        response.attribute.id,
                    };
                    $('.specification').append(compiledTemplate(context));

                    var template = $('#optionTemplate').html();
                    var compiledTemplate = Template7.compile(template);
                    $.each(response.attributeKeys, function (index, item) {
                        var context = {
                            value: item.id,
                            title: item.title
                        };
                        $('.added-specification-' + specificationId + ' #postSpecificationKeys').append(compiledTemplate(context))
                    });

                } else {
                    swal({
                        'title': 'دوباره سعی کنید!',
                        icon: 'warning',
                        button: 'تایید'
                    });
                }
            },
            error: function (xhr, status, error) {
                swal({
                    'title': 'دوباره سعی کنید!',
                    icon: 'warning',
                    button: 'تایید'
                });
            }
        });

    });

    $(document).on('click', '.delete-specification-key-values', function () {
        $(this).parent().parent().remove();
    });

    $(document).on('click', '.select-all', function () {
        $(this).parent().parent().find('select > option').prop("selected", true).trigger('change');
    });

    $(document).on('click', '.di-select-all', function () {
        $(this).parent().parent().find('select > option').prop("selected", false).trigger('change');
    });

    $(document).on('click', '.delete-attribute', function () {
        $(this).parent().parent().parent().parent().remove()
    });

</script>
