<?php

namespace LaraBase\LaraBase\Controllers;

use LaraBase\Attributes\Models\Attribute;
use LaraBase\Attributes\Models\AttributeKey;
use LaraBase\Attributes\Models\AttributeRelation;
use LaraBase\CoreController;
use LaraBase\Options\Models\Option;
use LaraBase\Posts\Models\PostType;

class InitController extends CoreController {
    
    public function init() {
    
        if (isDev()) {
            
            $appName = env('APP_NAME');
            
            if (!empty($appName)) {
    
                \Artisan::call('migrate');
                
                if (getOption('appInit') != 'complete') {
    
                    $appNameStrToLower = strtolower($appName);
                    $path = base_path("projects/{$appName}/config/{$appNameStrToLower}_install.php");
                    if (file_exists($path)) {
        
                        $config = config("{$appNameStrToLower}_install");
        
                        if (isset($config['postTypes'])) {
                            foreach ($config['postTypes'] as $postType) {
                                PostType::insert($postType);
                            }
                        }
        
                        if (isset($config['attributes'])) {
                            foreach ($config['attributes'] as $attribute) {
                                Attribute::insert($attribute);
                            }
                        }
        
                        if (isset($config['keys'])) {
                            foreach ($config['keys'] as $key) {
                                AttributeKey::insert($key);
                            }
                        }
        
                        if (isset($config['attribute_relations'])) {
                            foreach ($config['attribute_relations'] as $relation) {
                                AttributeRelation::insert($relation);
                            }
                        }
        
                        if (isset($config['themes'])) {
                            foreach ($config['themes'] as $key => $value) {
                                $old = Option::where("key", "{$key}Theme")->first();
                                if ($old == null) {
                                    Option::create([
                                        'key'   => $key,
                                        'value' => $value,
                                        'more'  => 'autoload'
                                    ]);
                                }
                                else {
                                    $old->update(['value' => $value]);
                                }
                            }
                        }
        
                    }
                
                    Option::create([
                        'key' => 'appInit',
                        'value' => 'complete',
                        'more' => 'autoload'
                    ]);
                    
                    \Artisan::call('cache:clear');
                
                }
            
            }
            
        }
        
        return redirect(route('admin.dashboard'));
        
    }
    
}
