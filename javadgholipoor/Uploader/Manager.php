<?php

namespace LaraBase\Uploader;

use Illuminate\Support\Facades\Hash;
use LaraBase\Options\Options;

class Manager {

    private $options;
    private $theme = null;

    public function load() {

        $hash = md5(ip() . url()->full());
        $value = json_encode($this->options);
        foreach ($this->options['validations'] as $key => $validation) {
            $cacheKey = "{$key}_{$hash}";
            setCache($cacheKey, $value, 360);
        }
        $key = $this->options['relationType'] . '_' . $this->options['relationId'];
        return uploaderView('master', ['uploaderKey' => $key], [], $this->theme);

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

    public function relation($type, $id) {
        $this->options['relationType'] = $type;
        $this->options['relationId'] = $id;
        return $this;
    }

    public function where($where) {
        $this->options['where'] = $where;
        return $this;
    }

}
