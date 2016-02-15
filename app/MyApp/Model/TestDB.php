<?php
namespace MyApp\Model;

\Larkspur::module('Database/Medoo');

class TestDB extends \Model {
    public function __construct() {}
    public function build() {
        return new \Medoo(
            array(
                'database_type' => 'sqlite',
                'database_file' => 'test.db'
            )
        );
    }
}

