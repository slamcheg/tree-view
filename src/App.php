<?php

namespace Application;

use Application\Components\Connection;
use Application\Interfaces\RouterInterface;
use Application\Interfaces\ViewManagerInterface;

/**
 * Class App
 * @package Application
 */
class App
{
    private $connection;

    public function __construct($config)
    {
        $this->setConnection(new $config['db']['class']($config['db']['config']));
    }
    /**
     * @return Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param Connection $connection
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;
    }
}