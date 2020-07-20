@if(isset($postTypeMetas['menus']['count']))
    <div class="box box-primary">

        <div class="box-header">
            <h3 class="box-title">فهرست ها</h3>
            <div class="box-tools">
                <i class="box-tools-icon icon-minus"></i>
            </div>
        </div>

        <div class="box-body tac">
            @foreach($postMenus as $i => $menuId)
                <a  href="{{ url("admin/menus/{$menuId}/edit") }}" target="_blank" class="btn dib mb5">ویرایش فهرست {{ $i+1 }}</a>
            @endforeach
        </div>

    </div>
@endif
