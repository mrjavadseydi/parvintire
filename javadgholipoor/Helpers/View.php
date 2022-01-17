<?php

namespace LaraBase\Helpers;

use LaraBase\Hook\Hook;

class View {

    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string|null  $view
     * @param  \Illuminate\Contracts\Support\Arrayable|array   $data
     * @param  array   $mergeData
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public static function authView($view = null, $data = [], $mergeData = [], $theme = null) {
        return self::view($view, $data, $mergeData, 'auth', $theme);
    }

    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string|null  $view
     * @param  \Illuminate\Contracts\Support\Arrayable|array   $data
     * @param  array   $mergeData
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public static function adminView($view = null, $data = [], $mergeData = [], $theme = null) {
        return self::view($view, $data, $mergeData, 'admin', $theme);
    }

    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string|null  $view
     * @param  \Illuminate\Contracts\Support\Arrayable|array   $data
     * @param  array   $mergeData
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public static function templateView($view = null, $data = [], $mergeData = [], $theme = null) {
        return self::view($view, $data, $mergeData, 'template', $theme);
    }

    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string|null  $view
     * @param  \Illuminate\Contracts\Support\Arrayable|array   $data
     * @param  array   $mergeData
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public static function emailView($view = null, $data = [], $mergeData = [], $theme = null) {
        return self::view($view, $data, $mergeData, 'email', $theme);
    }

    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string|null  $view
     * @param  \Illuminate\Contracts\Support\Arrayable|array   $data
     * @param  array   $mergeData
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public static function uploaderView($view = null, $data = [], $mergeData = [], $theme = null) {
        return self::view($view, $data, $mergeData, 'uploader', $theme);
    }

    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string|null  $view
     * @param  \Illuminate\Contracts\Support\Arrayable|array   $data
     * @param  array   $mergeData
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public static function errorView($view = null, $data = [], $mergeData = [], $theme = null) {
        return self::view($view, $data, $mergeData, 'errors', $theme);
    }

    public static function view($view, $data, $mergeData, $directory, $setTheme) {
        if ($setTheme == null)
            $theme = doAction('themes');
        else
            $theme[$directory] = $setTheme;

        $loadView ="{$directory}.{$theme[$directory]}.{$view}";

        if (!view()->exists($loadView)) {
            $defaultTheme = config("optionsConfig.defaultTheme.{$directory}");
            $loadView = "{$directory}.{$defaultTheme}.{$view}";
        }

        if ($directory == 'admin') {
            if (view()->exists("template.{$theme['template']}.admin.{$view}")) {
                $loadView = "template.{$theme['template']}.admin.{$view}";
            }
        }

        return view($loadView, $data, $mergeData);
    }

    public static function minify($buffer)
    {

        return preg_replace([
            "/>\n\s+</",
        ], [
            "><",
        ], $buffer);

        $replace = array(
            '/<!--[^\[](.*?)[^\]]-->/s' => '',
            "/\r/"                      => '',
            "/>\n</"                    => '><',
            "/>\s+\n</"                 => '><',
            "/>\n+</"                 => '><',
        );

        $search = [
            '/\>[^\S ]+/s',
            '/[^\S ]+\</s',
            '/(\s)+/s',
            '/<!--(.|\s)*?-->/'
        ];

        $replace = [
            '>',
            '<',
            '\\1',
            ''
        ];

        $buffer = preg_replace($search, $replace, $buffer);
        return $buffer;

    }

}
