<?php

function getUserPostTypes($user = null) {
    
    if (empty($user))
        $user = auth()->user();
    
    $canSetPostTypes = $user->canSetPostTypes();
    return $canSetPostTypes['postTypes'];
    
}

function getUserPostStatuses($user = null) {
    if (empty($user))
        $user = auth()->user();
    
    $statuses = [];
    $statusConfig = config('statusConfig');
    $canSetPostTypes = $user->canSetPostTypes();
    
    foreach ($canSetPostTypes['groups']['status'] as $status) {
        if (isset($statusConfig[$status]))
            $statuses[$status] = $statusConfig[$status];
    }
    
    return $statuses;
}
