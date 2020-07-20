<?php

use LaraBase\Options\Options;

function options() {
    $options = new Options();
    return $options->manager();
}

function getOption($key) {
    return Options::get($key);
}

function getOptionImage($key, $width = false, $height = false) {
    return Options::getImage($key, $width, $height);
}

function siteCurrency($field = 'value', $lang = null) {
    return Options::siteCurrency($field, $lang);
}

function siteName($lang = null) {
    return Options::siteName($lang);
}

function defaultSiteLogo($returnPath = false) {
    return Options::defaultSiteLogo($returnPath);
}

function defaultSiteTextLogo($returnPath = false) {
    return Options::defaultSiteTextLogo($returnPath);
}

function defaultFavicon($returnPath = false) {
    return Options::defaultFavicon($returnPath);
}

function siteLogo($returnPath = false) {
    return Options::siteLogo($returnPath);
}

function siteTextLogo($returnPath = false) {
    return Options::textLogo($returnPath);
}

function favicon($returnPath = false) {
    return Options::favicon($returnPath);
}

function siteKeywords($lang = null) {
    return Options::siteKeywords($lang);
}

function siteDescription($lang = null) {
    return Options::siteDescription($lang);
}

function siteAdminName($lang = null) {
    return Options::siteAdminName($lang);
}

function siteAdminFamily($lang = null) {
    return Options::siteAdminFamily($lang);
}

function siteMobile($lang = null) {
    return Options::siteMobile($lang);
}

function sitePhone($lang = null) {
    return Options::sitePhone($lang);
}

function siteFax($lang = null) {
    return Options::siteFax($lang);
}

function siteEmail($lang = null) {
    return Options::siteEmail($lang);
}

function sitePostalCode($lang = null) {
    return Options::sitePostalCode($lang);
}

function siteAddress($lang = null) {
    return Options::siteAddress($lang);
}

function siteCopyright($lang = null) {
    return Options::siteCopyright($lang);
}

function serverEmail() {
    return Options::serverEmail();
}

function serverEmailPassword() {
    return Options::serverEmailPassword();
}

function serverEmailHost() {
    return Options::serverEmailHost();
}

function serverEmailPort() {
    return Options::serverEmailPort();
}

function serverEmailType() {
    return Options::serverEmailType();
}

function getUploadPath() {
    return Options::uploadPath();
}

function getPluginsPath() {
    return Options::pluginsPath();
}

function getFontsPath() {
    return Options::fontsPath();
}

function uploadUrl($path = false){
    $uploadPath = getUploadPath();
    $uploadUrl = url("/{$uploadPath}") . '/';
    if ($path != false) {
        return $uploadUrl . $path;
    }
    return $uploadUrl;
}

function uploadPath($path = false){
    $uploadPath = getUploadPath();
    $uploadPath = public_path("/{$uploadPath}") . '/';
    if ($path != false) {
        return $uploadPath . $path;
    }
    return $uploadPath;
}

function plugins($path = false){
    $pluginsPath = getPluginsPath();
    $pluginsPath = asset("{$pluginsPath}") . '/';
    if ($path != false) {
        return $pluginsPath . $path;
    }
    return $pluginsPath;
}

function fonts($path = false){
    $fontsPath = getFontsPath();
    $fontsPath = asset("{$fontsPath}") . '/';
    if ($path != false) {
        return $fontsPath . $path;
    }
    return $fontsPath;
}

function formValidations($validations) {
    $key = 'formValidations';
    $value = json_encode($validations);
    if (Options::where('key', $key)->exists()) {
        Options::where('key', $key)->update([
            'value' => $value,
        ]);
    } else {
        Options::create([
            'key'   => $key,
            'value' => $value,
        ]);
    }
}

function formValidator($request) {
    $key = 'formValidations';
    $validations = [];
    if (Options::where('key', $key)->exists()) {
        $option = Options::where('key', $key)->first();
        $validations = json_decode($option->value, true);
    }
    return $request->validate($validations['rules'] ?? [], $validations['messages'] ?? []);
}

function formPermission($permission) {
    $key = 'formPermission';
    if (Options::where('key', $key)->exists()) {
        Options::where('key', $key)->update([
            'value' => $permission,
        ]);
    } else {
        Options::create([
            'key'   => $key,
            'value' => $permission,
        ]);
    }
}

function formPermissionValidate() {
    $key = 'formPermission';
    if (Options::where('key', $key)->exists()) {
        $option = Options::where('key', $key)->first();
        can($option->value);
    }
}

function isDev() {
    if (auth()->check()) {
        if (auth()->user()->hasMeta(config('optionsConfig.dev')))
            return true;
    }
    return false;
}
