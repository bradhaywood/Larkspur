<?php
namespace MyApp\Controller;

class Info extends \Controller {
    /**
      * @route /info/stuff
      * @method get
      */
    public static function info() {
        echo "Informationings!";
    }
}
