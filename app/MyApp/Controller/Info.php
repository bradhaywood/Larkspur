<?php
namespace MyApp\Controller;

class Info extends \Controller {
    /**
      * @route /info/stuff
      * @method get
      */
    public static function info() {
        $mod = \Model::get('TestDB');
        $users = $mod->table('users');
        \View::detach(array("users" => $users->search()));
    }
    
    /**
     * @route /info/products
     * @method get
     */
    public static function products() {
        $mod = \Model::get('TestDB');
        $prods = $mod->table('products');
        \View::detach(array("products" => $prods->search()));
    }
}
