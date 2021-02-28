<?php

use EasyMysql\Config;
use EasyMysql\Connection\MysqliConnection;
use EasyMysql\Connection\PDOConnection;
use EasyMysql\Enum\MysqlDriver;
use Psr\Container\ContainerInterface;

return [
    Config::class => DI\create()->constructor(
        DI\get('easyMysqlDriver'),
        DI\get('easyMysqlHost'),
        DI\get('easyMysqlPort'),
        DI\get('easyMysqlUser'),
        DI\get('easyMysqlPass'),
        DI\get('easyMysqlName'),
        ''
    ),
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
