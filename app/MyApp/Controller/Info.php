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
}
