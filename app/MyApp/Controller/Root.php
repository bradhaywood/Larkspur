<?php
namespace MyApp\Controller;

class Root extends \Controller {
    public static function auto()
    {
        echo "Auto ran first!<br>";
    }

    /**
     *  @route / 
     *  @method get
     */
    public function index() {
        if ($test = \Params::get('test'))
            echo $test . "<br>";

        echo "Hello world!";
        echo "<br>The app is <strong>". \Larkspur::$app . "</strong>";
    }

    /**
     * @route /about-us
     * @method get
     */
    public function about() {
        \Session::stash(array('title' => 'Test World', 'para' => 'Hello, from Larkspur'));
        \View::detach();
    }

    /**
     * @route /hello/:name
     * @method get
     */
    public static function hello($name) {
        \View::detach(array('name' => $name));
    }

    /**
     * @route /code/:code/name/:name
     * @method get
     */
    public static function get_code($code, $name) {
        echo "Code: $code, Name: $name";
    }

    /**
     * @root say bridge
     */
    public static function say_bridge() {
        $args = func_get_args();
        if ($args[0] == "World")
            return true;

        return false;
    }

    /**
     * @route /say/:name
     * @method get
     * @bridge say_bridge
     */
    public static function say_name($name) {
        echo "G'day there, $name";
    }
}
