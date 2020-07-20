<div class="row">

    <div class="col-lg-8">

        <div class="sticky-top top55 box box-info">
            @csrf

            <input type="hidden" name="id" value="{{ $id }}">
            <input type="hidden" name="key" value="{{ $key }}">

            <div class="box-header">
                <h3 class="box-title">دیویدر</h3>
                <div class="box-tools">
                    <i class="box-tools-icon icon-minus"></i>
                </div>
            </div>

            <div class="box-body">

                <div class="input-group">
                    <label>نوع</label>
                    <select class="w100" name="divider[type]">
                        <option {{ (isset($values->divider) ? selected($values->divider->type, 1) : "") }} value="1">دیویدر ۱</option>
                        <option {{ (isset($values->divider) ? selected($values->divider->type, 2) : "") }} value="2">دیویدر ۲</option>
                        <option {{ (isset($values->divider) ? selected($values->divider->type, 3) : "") }} value="3">دیویدر 3</option>
                    </select>
                </div>

                <div class="input-group">
                    <label>عنوان</label>
                    <input type="text" name="divider[title]" value="{{ (isset($values->divider) ? $values->divider->title : "") }}">
                </div>

                <div class="input-group">
                    <label>توضیحات</label>
                    <input type="text" name="divider[description]" value="{{ (isset($values->divider) ? $values->divider->description : "") }}">
                </div>

            </div>

            <div class="box-footer tal">
                <button class="btn-lg btn-success">ذخیره</button>
            </div>

        </form>

        <style>
            .imageBuilder {
                max-width: 100%;
            }
        </style>

    </div>

    </div>

    <div class="col-lg-4">

        @include("admin.posts.pageBuilder.tools.responsive")
        @include("admin.posts.pageBuilder.tools.borderRadius")
        @include("admin.posts.pageBuilder.tools.margin")
        @include("admin.posts.pageBuilder.tools.padding")

    </div>

</div>