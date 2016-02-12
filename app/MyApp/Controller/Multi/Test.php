<?php
namespace MyApp\Controller\Multi;

class Test extends \Controller {
    /**
     * @route /multi/test
     * @method get
     */
    public static function testing() {
        \View::detach(array("text" => "It works!"));
    }
}
