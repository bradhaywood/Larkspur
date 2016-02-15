<?php
namespace MyApp\Controller;

class Info extends \Controller {
    /**
      * @route /info/stuff
      * @method get
      */
    public static function info() {
        $mod = \Model::get('TestDB');
        \View::detach(array("users" => $mod->select('users', ['name', 'status'])));
    }
    
    /**
     * @route products
     * @method get
     */
    public static function products() {
        $mod = \Model::get('TestDB');
        $prods = $mod->select('products',['name', 'price']);
        \View::detach(array("products" => $prods));
    }
}
