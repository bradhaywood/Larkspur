<?php
namespace MyApp\Controller\Multi;

class Test extends \Controller {
    /**
     * @route index
     * @method get
     */
    public static function testing() {
        \View::detach(array("text" => "It works!"));
    }

    /**
     * @route another
     * @method get
     */
    public static function another() { echo "<h2>Testing</h2><p>Just testing</p>"; }

    /**
     * @route extended/url
     * @method get
     */
    public static function extended() { echo "<h2>Extended URL</h2>"; }
}
