<?php

function sidebar() {
    
    $theme = getTheme('admin');
    
    $appName = env('APP_NAME');
    if (!empty($appName)) {
        $appNameStrToLower = strtolower($appName);
        if (file_exists(base_path("projects/{$appName}/config/{$appNameStrToLower}_sidebar.php"))) {
            return config("{$appNameStrToLower}_sidebar.{$theme}") ?? [];
        }
    }
    
    return [];

}

function sidebarPermission($sidebar) {
    
    if (isset($sidebar['permission'])) {
        if (!empty($sidebar['permission'])) {
            if (!can($sidebar['permission'], true)) {
                return false;
            }
        }
    }
    
    return true;
    
}

function sidebarHref($sidebar) {
    if (isset($sidebar['href'])) {
        if (!empty($sidebar['href'])) {
            return url($sidebar['href']);
        }
    }
    
    return '#';
}

function sidebarTreeView($sidebar) {
    if (isset($sidebar['treeview'])) {
        return 'treeview';
    }
    
    return '';
}
