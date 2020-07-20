<?php

function delete($params) {

    echo '
        <form style="display: inline-block;" class="d-inline-block align-middle" method="post" action="'.route('admin.delete').'">
            <input type="hidden" name="_token" value="'.csrf_token().'">
            <input type="hidden" name="action" value="'.$params['action'].'">
            <input type="hidden" name="title" value="'.$params['title'].'">
            <input type="hidden" name="referer" value="'.\Request::fullUrl().'">
            <button href="" title="حذف" class="jgh-tooltip '. ($params['icon'] ?? 'fa fa-trash-alt') .' text-danger mb-0 h5"></button>
        </form>
    ';

}

function search($params) {
    $params['output'] = 'json';
    $coreController = new \LaraBase\CoreController();
    return $coreController->search($params);
}
