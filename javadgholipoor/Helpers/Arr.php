<?php
namespace LaraBase\Helpers;

class Arr
{
    public static function isJson($string)
    {
        @json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
    * Check value to find if it was serialized.
    *
    * If $data is not an string, then returned value will always be false.
    * Serialized data is always a string.
    *
    * @param string $data   Value to Check to see if was serialized.
    * @param bool   $strict Optional. Whether to be strict about the end of the string. Default true.
    * @return bool False if not serialized and true if it was.
    */
    public static function isSerialize( $data, $strict = true ) {
        // if it isn't a string, it isn't serialized.
        if ( ! is_string( $data ) ) {
            return false;
        }
        $data = trim( $data );
        if ( 'N;' == $data ) {
            return true;
        }
        if ( strlen( $data ) < 4 ) {
            return false;
        }
        if ( ':' !== $data[1] ) {
            return false;
        }
        if ( $strict ) {
            $lastc = substr( $data, -1 );
            if ( ';' !== $lastc && '}' !== $lastc ) {
                return false;
            }
        } else {
            $semicolon = strpos( $data, ';' );
            $brace     = strpos( $data, '}' );
            // Either ; or } must exist.
            if ( false === $semicolon && false === $brace ) {
                return false;
            }
            // But neither must be in the first X characters.
            if ( false !== $semicolon && $semicolon < 3 ) {
                return false;
            }
            if ( false !== $brace && $brace < 4 ) {
                return false;
            }
        }
        $token = $data[0];
        switch ( $token ) {
            case 's':
                if ( $strict ) {
                    if ( '"' !== substr( $data, -2, 1 ) ) {
                        return false;
                    }
                } elseif ( false === strpos( $data, '"' ) ) {
                    return false;
                }
                // or else fall through
            case 'a':
            case 'O':
                return (bool) preg_match( "/^{$token}:[0-9]+:/s", $data );
            case 'b':
            case 'i':
            case 'd':
                $end = $strict ? '$' : '';
                return (bool) preg_match( "/^{$token}:[0-9.E-]+;$end/", $data );
        }

        return false;
    }

    public static function treeView($records, $options = []) {

        if ($records == null) {
            return [];
        }

        $id = 'id';
        if (isset($options['id']))
            $id = $options['id'];

        $title = 'title';
        if (isset($options['title']))
            $title = $options['title'];

        $parent = 'parent';
        if (isset($options['parent']))
            $parent = $options['parent'];

        $output = [];

        $i = 0;
        foreach ($records->where($parent, null) as $record) {
            $thisId = $record->$id;
            $output[$i] = [
                'id'    => $thisId,
                'title' => $record->$title,
                'level' => 1,
                'record'=> $record
            ];
            if ($records->where($parent, $thisId)->count() > 0) {
                $output[$i]['list'] = self::subTreeView($thisId, $records, $options, 1);
            }
            $i++;
        }

        return $output;

    }

    public static function subTreeView($recordId, $records, $options, $counter) {

        $output = [];

        $id = 'id';
        if (isset($options['id']))
            $id = $options['id'];

        $title = 'title';
        if (isset($options['title']))
            $title = $options['title'];

        $parent = 'parent';
        if (isset($options['parent']))
            $parent = $options['parent'];

        $i = 0;
        foreach ($records->where($parent, $recordId) as $record) {
            $thisId = $record->$id;

            $output[$i] = [
                'id'     => $thisId,
                'title'  => $record->$title,
                'level'  => ++$counter,
                'record' => $record
            ];

            if ($records->where($parent, $thisId)->count() > 0) {
                $output[$i]['list'] = self::subTreeView($thisId, $records, $options, $counter);
            }

            $i++;

        }

        return $output;

    }

    public static function selectOptions($selected, $records, $options = []) {
        $output = null;
        foreach (self::treeView($records, $options) as $record) {
            $output .= '<option '.(in_array($record['id'], $selected) ? 'selected' : '').' class="level-0" value="'.$record['id'].'">'.$record['title'].'</option>';
            if (isset($record['list'])) {
                $output .= self::subOptions($selected, $record['list'], 0);
            }
        }
        return $output;
    }

    public static function subOptions($selected, $records, $counter) {

        $counter = intval($counter) + 1;

        $space = "&nbsp;";
        for ($j = 0; $j < ($counter * 2); $j++)
            $space .= "&nbsp;";

        $output = null;
        foreach ($records as $record) {
            $output .= '<option '.(in_array($record['id'], $selected) ? 'selected' : '').' class="level-'.$counter.'" value="'.$record['id'].'">'.$space.$record['title'].'</option>';
            if (isset($record['list'])) {
                $output .= self::subOptions($selected, $record['list'], $counter);
            }
        }

        return $output;

    }

    public static function checkbox($selected, $records, $name = 'categories', $options = []) {
        $output = null;
        foreach (self::treeView($records, $options) as $record) {
            $output .= "<li class='".($options['class'] ?? '')."'>";
            $output .= "<label for='for-{$record['id']}'>";
            $output .= "<input " . (in_array($record['id'], $selected) ? "checked" : "" ) . " class='level-0' id='for-{$record['id']}' value='{$record['id']}' name='".$name."[]' type='checkbox'> ";
            $output .= $record['title'];
            $output .= "</label>";
            if (isset($record['list'])) {
                $output .= "<ul class='children'>";
                $output .= self::subCheckbox($selected, $record['list'], $name);
                $output .= "</ul>";
            }
            $output .= "</li>";
        }
        return $output;
    }

    public static function subCheckbox($selected, $records, $name, $counter = 0) {

        $counter = intval($counter) + 1;

        $output = null;
        foreach ($records as $record) {
            $output .= "<li>";
            $output .= "<label for='for-{$record['id']}'>";
            $output .= "<input " . (in_array($record['id'], $selected) ? "checked" : "" ) . " class='level-".$counter."' id='for-{$record['id']}' value='{$record['id']}' name='".$name."[]' type='checkbox'> ";
            $output .= $record['title'];
            $output .= "</label>";
            if (isset($record['list'])) {
                $output .= "<ul class='children'>";
                $output .= self::subCheckbox($selected, $record['list'], $name, $counter);
                $output .= "</ul>";
            }
            $output .= "</li>";
        }

        return $output;

    }

}
