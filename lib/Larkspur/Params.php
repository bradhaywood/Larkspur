<?php
class Params {
    public function __construct() {}
    public static function get($key) {
        return $_GET[$key];
    }
}
