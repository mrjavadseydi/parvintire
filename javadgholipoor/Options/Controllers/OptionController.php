<?php


namespace LaraBase\Options\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use LaraBase\CoreController;
use LaraBase\Options\Models\Option;
use LaraBase\Posts\Models\Language;

class OptionController extends CoreController {

    public function general() {
        can('generalSetting');
        $languages = Language::all();
        return adminView('options.general', compact('languages'));
    }

    public function images() {
        can('imagesSetting');
        return adminView('options.images');
    }

    public function themeValues() {
        can('themeValuesSetting');
        return adminView('options.themeValues');
    }

    public function protocol() {
        can('protocolSetting');
        deleteCache('httpProtocol');
        return adminView('options.protocol');
    }

    public function language() {
        can('languageSetting');
        return adminView('options.language');
    }

    public function update(Request $request) {

        formPermissionValidate();
        formValidator($request);

        if ($request->has('options')) {

            $mores = [];
            if ($request->has('more')) {
                $mores = $request->more;
            }

            foreach ($request->options as $key => $value) {

                $option = [
                    'key'   => $key,
                    'value' => $value
                ];

                if (isset($mores[$key])) {
                    $option['more'] = $mores[$key];
                }

                $where = ['key' => $key];
                $where = ['key' => $key];

                if ($request->has('lang')) {
                    $option['lang'] = $request->lang;
                    $where['lang'] = $request->lang;
                }

                if (Option::where($where)->exists()) {
                    Option::where($where)->update($option);
                } else {
                    Option::create($option);
                }

            }
        }

        if (hasCache('options'))
            deleteCache('options');

        if (hasCache('optionsByLang'))
            deleteCache('optionsByLang');

        return redirect()->back()->with('success', 'بروزرسانی با موفقیت انجام شد');

    }

    public function checkForUpdates() {

        $success = false;
        $updateApp = false;
        $updateThemes = true;

        $appName = env('APP_NAME');
        $result = checkForUpdates();

        if ($result['status'] == 'success') {
            $success = true;

            if ($result['version'] != getVersion()) {
                $updateApp = true;
            } else {
                if (isset($result['project'])) {
                    if ($result['project']['version'] != getProjectVersion($appName)) {
                        $updateApp = true;
                    }
                }
            }
        }

        return adminView('options.check-for-updates', compact('success', 'updateApp', 'updateThemes'));

    }

}
