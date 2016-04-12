<?php
namespace MyApp\Model;

\Larkspur::module('Database/Medoo');

class TestDB extends \Model, \Medoo {
    public function __construct() {}
    public function build() {
        return new \Medoo(
            array(
                'database_type' => 'sqlite',
                'database_file' => 'test.db'
            )
        );
    }

    public static function sayHello() {
        echo "Hello!";
    }
}

