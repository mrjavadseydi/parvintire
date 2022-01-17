<?php


namespace LaraBase\Store\Controllers;


use LaraBase\CoreController;
use LaraBase\Store\Models\Product;

class ProductController extends CoreController {
    
    
    
    public function search() {
        
        $term = null;
        if (isset($_GET['term']))
            $term = $_GET['term'];
        
        $records = Product::where('title', 'like', "%{$term}%")->limit(100)->get();
        
        $output = [
            'items' => []
        ];
        
        foreach ($records as $record) {
            $output['items'][] = [
                'id'  => $record->id,
                'text' => $record->title,
            ];
        }
        
        return response()->json($output);
        
    }
    
}
