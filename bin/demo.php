<?php
/** @noinspection PhpComposerExtensionStubsInspection */
declare(strict_types=1);
ini_set('display_errors', 'On');
error_reporting(E_ALL);

use EasyMysql\Config;
use EasyMysql\EasyMysql;
use EasyMysql\Enum\MysqlDriverEnum;

error_reporting(E_ALL);

require_once '../vendor/autoload.php';

$builder = new DI\ContainerBuilder();
$builder->addDefinitions([
    Config::class => DI\create()->constructor(
        (random_int(0,1000) < 500 ? MysqlDriverEnum::PDO() : MysqlDriverEnum::MYSQLI()),
        '165.227.154.188',
        'uroot3',
        'fPEKP!u@93mc',
        null,
        3306
    )
]);
$container = $builder->build();

$dataProvider = $container->get(EasyMysql::class);


$ret = $dataProvider->fetchFirstColumn("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? LIMIT 5", ['mysql']);
print_r($ret);

$ret = $dataProvider->fetchAllKeyValue('SELECT TABLE_NAME, TABLE_ROWS FROM information_schema.TABLES WHERE TABLE_SCHEMA = \'mysql\' LIMIT 5');
print_r($ret);

$ret = $dataProvider->fetchAllAssociative('SELECT TABLE_NAME, TABLE_ROWS FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? LIMIT 5', ['mysql']);
print_r($ret);

foreach (
    $dataProvider->iterateKeyValue("SELECT CONCAT(TABLE_SCHEMA,'.',TABLE_NAME,'.',COLUMN_NAME), DATA_TYPE FROM information_schema.COLUMNS LIMIT 10,5")
    as $key=>$value
) {
    echo json_encode([$key => $value], JSON_THROW_ON_ERROR)."\n";
}

//end

