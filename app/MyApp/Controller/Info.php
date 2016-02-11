<?php
namespace MyApp\Controller;

class Info extends \Controller {
    /**
      * @route /info/stuff
      * @method get
      */
    public static function info() {
        $mod = \Model::get('TestDB');
        echo "Informationings: " . $mod['foo'];
    }
}
