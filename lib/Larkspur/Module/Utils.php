<?php
namespace Larkspur\Module;

class Utils {
    public static function getDocComment($str, $tag = '') { 
        if (empty($tag)) {
            return $str; 
        } 

        $matches = array(); 
        preg_match("/".$tag." (.*)(\\r\\n|\\r|\\n)/U", $str, $matches); 

        if (isset($matches[1])) {
            return trim($matches[1]); 
        } 

        return ''; 
    }
}
