<?php


namespace LaraBase\App\Controllers;


use LaraBase\CoreController;
use LaraBase\Posts\Models\Language;

class LanguageController extends CoreController
{

    public function set($lang)
    {
        $get = Language::where('lang', $lang)->first();
        if ($get != null) {
            if (hasCookie('locale'))
                deleteCookie('locale');

            setCookiee('locale', $lang);
        }

        if (isset($_GET['url']))
            return redirect($_GET['url']);

        return redirect(url('/'));

    }

    public function get()
    {
        return app()->getLocale();
    }

}
