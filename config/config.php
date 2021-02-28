<?php

use EasyMysql\Config;
use EasyMysql\Connection\MysqliConnection;
use EasyMysql\Connection\PDOConnection;
use EasyMysql\Enum\MysqlDriver;
use Psr\Container\ContainerInterface;

return [
   /*Config::class => DI\create()->constructor(
       MysqlDriver::MYSQLI(),
       DI\env('DATABASE_HOST', 'localhost'),
       DI\env('DATABASE_PORT', 3306),
       DI\env('DATABASE_USER', 'root'),
       DI\env('DATABASE_PASS', ''),
       DI\env('DATABASE_NAME', null),
       ''
   ),*/
   EasyMysql\Connection\ConnectionInterface::class => DI\factory(function (Config $config, ContainerInterface $container) {
        if ($config->getMysqlDriver()->equals(MysqlDriver::MYSQLI())) {
            return $container->get(MysqliConnection::class);
        }
        if ($config->getMysqlDriver()->equals(MysqlDriver::PDO())) {
            return $container->get(PDOConnection::class);
        }
        throw new RuntimeException('Cannot build connection - bad driver specified ');
    })
];
