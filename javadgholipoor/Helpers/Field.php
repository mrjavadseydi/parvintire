<?php

namespace LaraBase\Helpers;

/**
 * Class Field
 * @package Larabase\Cms\Helper
 */
class Field
{
    /**
     * Selected
     * add attribute selected
     * @param string $name
     * @param string $value
     * @return string
     */
    public static function selected($name, $value)
    {
        if ($name == $value)
            return 'selected';

        return null;
    }

    /**
     * Checked
     * add attribute checkbox
     * @param string $name
     * @param string $value
     * @return string
     */
    public static function checked($name, $value)
    {
        if ($name == $value)
            return 'checked';

        return null;
    }
}
