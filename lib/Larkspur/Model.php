<?php

class Model {
    private static $models = array();
    public function __construct() {
        self::$models = $models;
    }

    public static function model_exists($key, $arr=false) {
        $search_arr = self::$models;
        if (isset($arr) && is_array($arr))
            $search_arr = $arr;

        if (array_key_exists($key, $search_arr)) {
            return true;
        }

        foreach ($search_arr as $element) {
            if (is_array($element)) {
                if (self::model_exists($key, $element)) {
                    return $element;
                }
            }

        }

        return false;
    }

    public function init($model, $object) {
        if (isset($model) && isset($object)) {
            if (is_object($object) || is_array($object)) {
                $v = array($model => $object);
                array_push(self::$models, $v);
            }
            else {
                fputs(STDERR, "Cannot load model ${model} because we weren't given a valid object\n");
                return false;
            }
        }

        return false;
    }
    public function get($model) {
        if ($res = self::model_exists($model))
            return $res[$model];
        
        return false;
    }
}
