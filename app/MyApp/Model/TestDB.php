<?php
namespace MyApp\Model;

\Larkspur::module('Database/Jellybean');

class TestDB extends \Model {
    public function __construct() {}
    public function build() {
        return new \Jellybean(
            array(
                'driver' => 'sqlite',
                'dbname' => 'test.db'
            )
        );
    }
}

