<?php

namespace LaraBase\Uploader;

class Manager {

    private $options;
    private $theme = null;

    public function load($returnView = true) {

        $url = url()->full();
        if (\Request::is('api*')) {
            $url = $_SERVER['HTTP_REFERER'] ?? url()->full();
        }
        $hash = uploaderHash($url);
        $value = json_encode($this->options);
        foreach ($this->options['validations'] as $key => $validation) {
            $cacheKey = "{$key}_{$hash}";
            setCache($cacheKey, $value, 360);
        }
        if ($returnView) {
            $key = $this->options['relationType'] . '_' . $this->options['relationId'];
            return uploaderView('master', ['uploaderKey' => $key], [], $this->theme);
        }

    }

    public function init()
    {
        $this->load(false);
    }

    public function theme($theme)
    {
        $this->theme = $theme;
        return $this;
    }

    function enableYearDir() {
        $this->options['yearDir']   = true;
        $this->options['monthDir']  = false;
        $this->options['dayDir']    = false;
        $this->options['hourDir']   = false;
        return $this;
    }

    function enableMonthDir() {
        $this->options['yearDir']   = true;
        $this->options['monthDir']  = true;
        $this->options['dayDir']    = false;
        $this->options['hourDir']   = false;
        return $this;
    }

    function enableDayDir() {
        $this->options['yearDir']   = true;
        $this->options['monthDir']  = true;
        $this->options['dayDir']    = true;
        $this->options['hourDir']   = false;
        return $this;
    }

    function enableHourDir() {
        $this->options['yearDir']   = true;
        $this->options['monthDir']  = true;
        $this->options['dayDir']    = true;
        $this->options['hourDir']   = true;
        return $this;
    }

    function disableYearDir() {
        $this->options['yearDir']   = false;
        $this->options['monthDir']  = false;
        $this->options['dayDir']    = false;
        $this->options['hourDir']   = false;
        return $this;
    }

    function disableMonthDir() {
        $this->options['monthDir']  = false;
        $this->options['dayDir']    = false;
        $this->options['hourDir']   = false;
        return $this;
    }

    function disableDayDir() {
        $this->options['dayDir']    = false;
        $this->options['hourDir']   = false;
        return $this;
    }

    function disableHourDir() {
        $this->options['hourDir']   = false;
        return $this;
    }

    function path($path) {
        $this->options['path'] = $path;
        return $this;
    }

    public function validations($validations) {
        $this->options['validations'] = $validations;
        return $this;
    }

    public function addKey($key, $uploadIn, $validations, $method = null, $data = [], $path = null, $year = null, $month = null, $day = null, $hour = null)
    {
        $add = [
            'key' => $key,
            'in' => $uploadIn,
            'validations' => $validations,
        ];
        if (!empty($method)) {
            $add['method'] = $method;
        }
        if (!empty($data)) {
            $add['data'] = $data;
        }
        if (!empty($path)) {
            $add['path'] = $path;
        }
        if ($year !== null) {
            $add['year'] = $year;
        }
        if ($month !== null) {
            $add['month'] = $month;
        }
        if ($day !== null) {
            $add['day'] = $day;
        }
        if ($hour !== null) {
            $add['hour'] = $hour;
        }
        if ($uploadIn == 5) {
            getUserDownloadServerToken();
        }
        $this->options['validations'][$key] = $add;
        return $this;
    }

    public function relation($type, $id) {
        $this->options['relationType'] = $type;
        $this->options['relationId'] = $id;
        return $this;
    }

    public function where($where) {
        $this->options['where'] = $where;
        return $this;
    }

    public function data($data)
    {
        $this->options['data'] = $data;
        return $this;
    }

}
