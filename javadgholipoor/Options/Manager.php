<?php

namespace LaraBase\Options;

use LaraBase\Attachments\Models\Attachment;
use LaraBase\Options\Models\Option;

class Manager extends Option {

    protected $table = 'options';

    public function __construct(array $attributes = []) {
        parent::__construct($attributes);
    }

    public function option($key, $return = 'value') {

        $options = [];
        if (hasCache('options')) {
            $options = json_decode(getCache('options'), true);
            if (isset($options[$key][$return])) {
                return $options[$key][$return];
            }
        }

        $get = Option::where('key', $key)->first();
        if ($get != null) {
            $value = $get->$return;
            $options[$key][$return] = $value;
            setCache('options', json_encode($options));
            return $value;
        }

    }

    public function optionByLang($key, $return = 'value', $lang = null)
    {

        if ($lang == null)
            $lang = app()->getLocale();

        $options = [];
        if (hasCache('optionsByLang')) {
            $options = json_decode(getCache('optionsByLang'), true);
            if (isset($options[$lang][$key][$return])) {
                return $options[$lang][$key][$return];
            }
        }

        $get = Option::where([
            'key' => $key,
            'lang' => $lang
        ])->first();

        if ($get != null) {
            $value = $get->$return;
            $options[$lang][$key][$return] = $value;
            setCache('optionsByLang', json_encode($options));
            return $value;
        }

    }

    public function get($key, $return = 'value') {
        return $this->option($key, $return);
    }

    public function getImage($key, $width = false, $height = false) {

        try {
            $href = '#';
            $alt = 'image';
            $target = 'self';
            $src = image('thumbnail.jpg', 'admin');

            $getImages = doAction('images');
            if (isset($getImages[$key])) {

                $images = $getImages[$key];
                $value = $images['value'];
                $more = $images['more'];

                $jsonDecode = json_decode($more, true);

                if (isset($jsonDecode['alt'])) {
                    $alt = $jsonDecode['alt'];
                }

                if (isset($jsonDecode['target'])) {
                    $target = $jsonDecode['target'];
                }

                if (isset($jsonDecode['href'])) {
                    $href = $jsonDecode['href'];
                }

                if (!empty($value)) {

                    if ($width != false && $height != false) {

                        $parts = explode('/', $value);
                        $lastIndex = count($parts) - 1;
                        $originalName = $parts[$lastIndex];
                        $resizeName   = "{$width}x{$height}-{$originalName}";
                        unset($parts[$lastIndex]);
                        $path = implode('/', $parts);

                        $parts = [
                            'originalName' => $originalName,
                            'resizeName'   => $resizeName,
                            'path'         => $path
                        ];

                        if (!file_exists("{$parts['path']}/{$parts['resizeName']}")) {
                            $img = \Intervention\Image\Facades\Image::make(public_path($parts['path'] . '/' . $parts['originalName']));
                            $filePath = "{$parts['path']}/{$parts['resizeName']}";
                            $img->fit($width, $height)->save($filePath);
                            $attachment = Attachment::where([
                                'path' => $value
                            ])->first();
                            if ($attachment != null) {
                                Attachment::create([
                                    'title'     => $attachment->title,
                                    'user_id'   => $attachment->user_id,
                                    'mime'      => $attachment->mime,
                                    'type'      => $attachment->type,
                                    'extension' => $attachment->extension,
                                    'size'      => filesize($filePath),
                                    'path'      => $filePath,
                                    'in_public' => '1',
                                    'parent'    => $attachment->id
                                ]);
                            }
                        }

                        $src = url("{$parts['path']}/{$parts['resizeName']}");

                    } else {
                        $src = url($value);
                    }

                }

            }

            return [
                'alt' => $alt,
                'href' => (empty($href)) ? '#' : $href,
                'target' => $target,
                'src' => $src
            ];
        } catch (\Exception $error) {
            return [
                'alt' => 'image source not found',
                'href' => '#',
                'target' => '#',
                'src' => '#'
            ];
        }

    }

    public function siteCurrency($field = 'value', $lang = null) {
        return $this->optionByLang('siteCurrency', $field, $lang);
    }

    public function siteName($lang = null) {
        return $this->optionByLang('name', 'value', $lang) ?? 'نام سایت';
    }

    public function defaultSiteLogo($returnPath = false) {
        return image('default-site-logo.png', 'admin', $returnPath);
    }

    public function defaultSiteTextLogo($returnPath = false) {
        return image('default-site-logo.png', 'admin', $returnPath);
    }

    public function defaultFavicon($returnPath = false) {
        return image('favicon.png', 'admin', $returnPath);
    }

    public function siteLogo($returnPath = false) {

        if (isset($this->options['siteLogo'])) {
            $logoPath = $this->options['siteLogo'];

            if ($returnPath)
                return $logoPath;

            return url($logoPath);
        }

        return defaultSiteLogo($returnPath);

    }

    function textLogo($returnPath = false)
    {
        if (isset($this->options['siteTextLogo'])) {
            $logoPath = $this->options['siteTextLogo'];

            if ($returnPath)
                return $logoPath;

            return url($logoPath);
        }

        return defaultSiteTextLogo($returnPath);
    }

    function favicon()
    {
        return getOptionImage('favicon', 16, 16)['src'];
    }

    function siteKeywords($lang = null)
    {
        return $this->optionByLang('keywords', 'value', $lang) ?? 'کلمات کلیدی';
    }

    function siteDescription($lang = null)
    {
        return $this->optionByLang('description', 'value', $lang) ?? 'توضیحات سایت';
    }

    function siteAdminName($lang = null)
    {
        return $this->optionByLang('adminName', 'value', $lang);
    }

    function siteAdminFamily($lang = null)
    {
        return $this->optionByLang('adminFamily', 'value', $lang);
    }

    function siteMobile($lang = null)
    {
        return $this->optionByLang('mobile', 'value', $lang) ?? '09376525788';
    }

    function sitePhone($lang = null)
    {
        return $this->optionByLang('phone', 'value', $lang);
    }

    function siteFax($lang = null)
    {
        return $this->optionByLang('fax', 'value', $lang);
    }

    function siteEmail($lang = null)
    {
        return $this->optionByLang('email', 'value', $lang) ?? 'apkmaker74@gmail.com';
    }

    function sitePostalCode($lang = null)
    {
        return $this->optionByLang('postalCode', 'value', $lang);
    }

    function siteAddress($lang = null)
    {
        return $this->optionByLang('address', 'value', $lang);
    }

    function siteCopyright($lang = null)
    {
        return $this->optionByLang('copyright', 'value', $lang);
    }

    function serverEmail() {
        return $this->option('serverEmail');
    }

    function serverEmailPassword() {
        return $this->option('serverEmailPassword');
    }

    function serverEmailHost() {
        return $this->option('serverEmailHost');
    }

    function serverEmailPort() {
        return $this->option('serverEmailPort');
    }

    function serverEmailType() {
        return $this->option('serverEmailType');
    }

    public function uploadPath() {
        return config('optionsConfig.uploads.path');
    }

    public function pluginsPath() {
        return config('optionsConfig.plugins.path');
    }

    public function fontsPath() {
        return config('optionsConfig.fonts.path');
    }

}
