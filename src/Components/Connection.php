<?php


namespace Application\Components;

use PDO;
use PDOException;

class Connection
{
    public $config;

    public function __construct($config = [])
    {
        try {
            $this->db = new PDO('mysql:host=' . $config['host'] . ';dbname=' . $config['db'], $config['user'], $config['password']);
        } catch (PDOException $e) {
            print "Connection Error: " . $e->getMessage();
            die();
        }
    }
}