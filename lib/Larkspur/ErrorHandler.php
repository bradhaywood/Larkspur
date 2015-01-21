<?php
namespace Larkspur;

class ErrorHandler {
    protected $code = null;
    public function __construct($code) {
        $this->code = $code;
    }

    public function hissy_fit() {
        if (! ($this->code))
            return false;

        switch($this->code) {
            case 403:
                $msg = "Not Authorized";
                break;
            case 404:
                $msg = "Page Not Found";
                break;
            case 400:
                $msg = "Bad Request Method";
                break;
        }

        die("<h1>$msg</h1>");
    }
}

        
