<?php
declare(strict_types=1);

use EasyMysql\Config;
use EasyMysql\EasyMysql;
use EasyMysql\Enum\MysqlDriverEnum;

error_reporting(E_ALL);

require_once '../vendor/autoload.php';

$builder = new DI\ContainerBuilder();
$builder->addDefinitions([
    Config::class => DI\create()->constructor(
        MysqlDriverEnum::PDO(),
        'host',
        'user',
        'pass',
        null,
        3306
    )
]);
$container = $builder->build();

$dataProvider = $container->get(EasyMysql::class);


$ret = $dataProvider->column("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? LIMIT 5", 'TABLE_NAME', ['mysql']);
print_r($ret);

$ret = $dataProvider->assoc01('SELECT TABLE_NAME, TABLE_ROWS FROM information_schema.TABLES WHERE TABLE_SCHEMA = "mysql" LIMIT 5');
print_r($ret);

$ret = $dataProvider->assocAll('SELECT TABLE_NAME, TABLE_ROWS FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? LIMIT 5', 'TABLE_NAME', ['mysql']);
print_r($ret);

