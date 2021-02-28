<?php

use EasyMysql\DataProvider;
use EasyMysql\Enum\MysqlDriver;

require_once '../vendor/autoload.php';

$builder = new DI\ContainerBuilder();
$builder->addDefinitions([
    'easyMysqlDriver' => MysqlDriver::MYSQLI(),
    'easyMysqlHost' => '192.168.24.24',
    'easyMysqlPort' => 3306,
    'easyMysqlUser' => 'devel',
    'easyMysqlPass' => 'withc--',
    'easyMysqlName' => null,
]);

$builder->addDefinitions('../config/config.php');
$container = $builder->build();

$dataProvider = $container->get(DataProvider::class);

$ret = $dataProvider->column("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? LIMIT 5", 'TABLE_NAME', ['!marketplace_local']);
print_r($ret);

$ret = $dataProvider->assoc('SELECT TABLE_NAME, TABLE_ROWS FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? LIMIT 5', 'TABLE_NAME', 'TABLE_ROWS', ['marketplace_local']);
print_r($ret);
