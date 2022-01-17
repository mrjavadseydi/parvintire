<?php


namespace LaraBase\Posts\Actions;


trait Authorizable {
    
    function canEdit($user = false, $canSetPostTypes = false, $returnBool = false) {
        if (!$user)
            $user = auth()->user();
    
        if (!$canSetPostTypes)
            $canSetPostTypes = $user->canSetPostTypes();
        
        $permissions = $canSetPostTypes['permissions'];
    
        if (in_array($this->post_type, $permissions['post_update']))
            return true;
        
        if ($returnBool)
            return false;
        
        return $this->abort();
    }
    
    function canDelete() {
    
    }
    
    function abort() {
        return abort(401);
    }
    
}
