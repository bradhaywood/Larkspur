<?php
class Params {
    public function __construct() {}
    public static function get($key) {
        if (isset($_GET[$key]))
            return $_GET[$key];

        return false;
    }
    public static function post($key) {
        if (isset($_POST[$key]))
            return $_POST[$key];
        
        return false;
    }
}
