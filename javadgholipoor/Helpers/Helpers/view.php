<?php

if (! function_exists('authView')) {
    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string|null  $view
     * @param  \Illuminate\Contracts\Support\Arrayable|array   $data
     * @param  array   $mergeData
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    function authView($view = null, $data = [], $mergeData = [], $theme = null)
    {
        return \LaraBase\Helpers\View::authView($view, $data, $mergeData, $theme);
    }
}

if (! function_exists('adminView')) {
    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string|null  $view
     * @param  \Illuminate\Contracts\Support\Arrayable|array   $data
     * @param  array   $mergeData
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    function adminView($view = null, $data = [], $mergeData = [], $theme = null)
    {
        return \LaraBase\Helpers\View::adminView($view, $data, $mergeData, $theme);
    }
}

if (! function_exists('templateView')) {
    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string|null  $view
     * @param  \Illuminate\Contracts\Support\Arrayable|array   $data
     * @param  array   $mergeData
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    function templateView($view = null, $data = [], $mergeData = [], $theme = null)
    {
        return \LaraBase\Helpers\View::templateView($view, $data, $mergeData, $theme);
    }
}

if (! function_exists('emailView')) {
    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string|null  $view
     * @param  \Illuminate\Contracts\Support\Arrayable|array   $data
     * @param  array   $mergeData
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    function emailView($view = null, $data = [], $mergeData = [], $theme = null)
    {
        return \LaraBase\Helpers\View::emailView($view, $data, $mergeData, $theme);
    }
}

if (! function_exists('uploaderView')) {
    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string|null  $view
     * @param  \Illuminate\Contracts\Support\Arrayable|array   $data
     * @param  array   $mergeData
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    function uploaderView($view = null, $data = [], $mergeData = [], $theme = null)
    {
        return \LaraBase\Helpers\View::uploaderView($view, $data, $mergeData, $theme);
    }
}

if (! function_exists('errorView')) {
    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string|null  $view
     * @param  \Illuminate\Contracts\Support\Arrayable|array   $data
     * @param  array   $mergeData
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    function errorView($view = null, $data = [], $mergeData = [], $theme = null)
    {
        return \LaraBase\Helpers\View::errorView($view, $data, $mergeData, $theme);
    }
}
