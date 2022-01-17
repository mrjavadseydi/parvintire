<?php

use LaraBase\Helpers\Field;

if(!function_exists('selected')){
    function selected($name, $value){
        return Field::selected($name, $value);
    }
}

if(!function_exists('checked')){
    function checked($name, $value){
        return Field::checked($name, $value);
    }
}

function sortItems($items, $sortField = 'sort', $titleField = 'title', $idField = 'id') {
    
    // TODO optimize
    
    $output = [];
    
    $i = 0;
    
    foreach ($items as $item) {
    
        $title = $item->$titleField;
        $thisSort = $item->$sortField;
    
        if ($i == 0) {
            $prevSort = $thisSort - 1;
            $output[] = [
                'id' => $item->$idField,
                'title' => 'قبل از ' . $title,
                'value' => ($thisSort + $prevSort) / 2
            ];
        }
    
        $nextSort = $thisSort + 1;
        if (isset($items[$i+1])) {
            $nextSort = $items[$i+1]->sort;
            if ($thisSort == $nextSort) {
                $nextSort = $nextSort + 0.1;
            }
        }
    
        $output[] = [
            'id' => $item->$idField,
            'title' => 'بعد از ' . $title,
            'value' => ($thisSort + $nextSort) / 2
        ];
    
        $i++;
        
    }
    
    return $output;
    
}
