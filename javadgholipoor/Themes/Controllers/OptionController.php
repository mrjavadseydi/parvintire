<?php


namespace LaraBase\Themes\Controllers;


class OptionController {
    
    public function themes() {
        
        $typeThemes = [
            'auth'          => [
                'default', 'hoorbook'
            ],
            'template'      => [
                'default', 'larabase', 'hoorbook', 'mersinTrade'
            ],
            'admin'         => [
                'default', 'hoorbook', 'aniDesign'
            ],
            'email'         => [
                'default'
            ],
            'uploader'      => [
                'default'
            ]
        ];
        
        return adminView('options.themes', compact('typeThemes'));
        
    }
    
}
