<?php /** @noinspection SqlNoDataSourceInspection */

declare(strict_types=1);
ini_set('display_errors', 'On');
error_reporting(E_ALL);

use EasyMysql\Config;
use EasyMysql\EasyMysql;
use EasyMysql\Enum\MysqlDriverEnum;
use EasyMysql\Exceptions\DuplicateEntryException;
use EasyMysql\Exceptions\EasyMysqlQueryException;

error_reporting(E_ALL);

require_once '../vendor/autoload.php';

$builder = new DI\ContainerBuilder();
$builder->addDefinitions([
    Config::class => DI\create()->constructor(
        MysqlDriverEnum::MYSQLI(),
        'localhost',
        'root',
        '',
        'easymysql',
        3306
    )
]);
$container = $builder->build();

$dataProvider = $container->get(EasyMysql::class);

echo "==============\n";
echo "Using PDO\n";
echo "==============\n";

try {
    $i = 0;
    echo (++$i) . ". Drop Table IF EXISTS : \n";
    $query = 'DROP TABLE IF EXISTS `easytest`';
    $dataProvider->dmlQuery($query);
    echo 'Affected Rows: [' . $dataProvider->affectedRows() . "]\n";

    echo (++$i) . ". Create Table : \n";
    $query = "CREATE TABLE `easytest` (`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT, `name` VARCHAR(100) NOT NULL DEFAULT '0',PRIMARY KEY (`id`), UNIQUE INDEX `name` (`name`))";
    $dataProvider->dmlQuery($query);
    echo 'Affected Rows: [' . $dataProvider->affectedRows() . "]\n";


    echo (++$i) . ". Insert Values: \n";
    $query = 'INSERT INTO `easytest` SET `name` = ?';
    $lastInsertId1 = $dataProvider->insert($query, ['Easy']);
    echo 'Last INSERT ID: [' . $lastInsertId1 . "]\n";

    echo (++$i) . ". Insert Values: \n";
    $query = 'INSERT INTO `easytest` SET `name` = ?';
    $lastInsertId11 = $dataProvider->insert($query, ['Easy2']);
    echo 'Last INSERT ID: [' . $lastInsertId11 . "]\n";

    echo (++$i) . ". Insert duplicate value: \n";
    $query = 'INSERT INTO `easytest` SET `name` = ?';
    try {
        $lastInsertId1 = $dataProvider->insert($query, ['Easy']);
    } catch (DuplicateEntryException $e) {
        echo 'DuplicateEntryException thrown!: [ErrCode ' . $e->getCode() . "]\n";
    }

    echo (++$i) . ". Insert wrong query: \n";
    $query = 'INSERT INTO `easytest` SET `bad-name` = ?';
    try {
        $lastInsertId1 = $dataProvider->insert($query, ['Easy']);
    } catch (EasyMysqlQueryException $e) {
        echo 'EasyMysqlQueryException thrown!: [ErrCode ' . $e->getCode() . "]\n";
    }

    echo (++$i) . ". Insert Values without binding : \n";
    $query = "INSERT INTO `easytest` SET `name`= 'No Binding'";
    $lastInsertId2 = $dataProvider->insert($query);
    echo 'Last INSERT ID: [' . $lastInsertId2 . "]\n";

    echo (++$i) . ". Insert Values without binding for deletion: \n";
    $query = "INSERT INTO `easytest` (`name`) VALUES ('To Delete 1')";
    $lastInsertIdDel1 = $dataProvider->insert($query);
    echo 'Last INSERT ID: [' . $lastInsertId2 . "]\n";
    $query = "INSERT INTO `easytest` (`name`) VALUES ('To Delete 2')";
    $lastInsertIdDel2 = $dataProvider->insert($query);
    echo 'Last INSERT ID: [' . $lastInsertId2 . "]\n";


    echo (++$i) . ". Insert Multiple Values without binding : \n";
    $query = "INSERT INTO `easytest` (`name`) VALUES ('mb1'), ('mb2'), ('Easy') ON DUPLICATE KEY UPDATE `name` = VALUES(`name`)";
    $lastInsertId2 = $dataProvider->insert($query);
    echo 'Last INSERT ID: [' . $lastInsertId2 . "]\n";

    echo (++$i) . ". Update Values : \n";
    $query = 'UPDATE `easytest` SET name = ? WHERE id =  ? ';
    $affectedRows = $dataProvider->update($query, ['Easy MySQL Update', $lastInsertId1]);
    echo 'Affected Rows: [' . $affectedRows . "]\n";

    echo (++$i) . ". Update Values without binding : \n";
    $query = "UPDATE `easytest` SET name= 'Really No Binding' WHERE id = " . (int)$lastInsertId2;
    $lastInsertId2 = $dataProvider->update($query);
    echo 'Last INSERT ID: [' . $lastInsertId2 . "]\n";

    echo (++$i) . ". Delete Values: \n";
    $query = 'DELETE FROM `easytest` WHERE id =  ? ';
    $affectedRows = $dataProvider->delete($query, [$lastInsertIdDel1]);
    echo 'Affected Rows: [' . $affectedRows . "]\n";

    echo (++$i) . ". Delete Values without binding: \n";
    $query = 'DELETE FROM `easytest` WHERE id = ' . (int)$lastInsertIdDel2;
    $affectedRows = $dataProvider->delete($query);
    echo 'Affected Rows: [' . $affectedRows . "]\n";

    echo (++$i) . ". fetchAllAssociative without binding: \n";
    $query = 'SELECT * FROM `easytest`';
    $array = $dataProvider->fetchAllAssociative($query);
    print_r($array);
    echo "\n";

    echo (++$i) . ". Select wrong query: \n";
    $query = 'SELECT * FROM `easytest` WHERE `bad-name` = ?';
    try {
        $lastInsertId1 = $dataProvider->fetchAllAssociative($query, ['Easy']);
    } catch (EasyMysqlQueryException $e) {
        echo 'EasyMysqlQueryException thrown!: [ErrCode ' . $e->getCode() . "]\n";
    }

    echo (++$i) . ". fetchAllAssociative with binding: \n";
    $query = 'SELECT * FROM `easytest` WHERE name LIKE ?';
    $array = $dataProvider->fetchAllAssociative($query, ['%E%']);
    print_r($array);
    echo "\n";

    echo (++$i) . ". fetchFirstColumn with binding: \n";
    $query = 'SELECT name FROM `easytest` WHERE name LIKE ?';
    $array = $dataProvider->fetchFirstColumn($query, ['%E%']);
    print_r($array);
    echo "\n";

    echo (++$i) . ". fetchFirstColumn without binding: \n";
    $query = "SELECT name FROM `easytest` WHERE name LIKE '%e%'";
    $array = $dataProvider->fetchFirstColumn($query);
    print_r($array);
    echo "\n";

} catch (EasyMysqlQueryException $easyMysqlQueryException) {
    echo 'ERROR in query : ' .$easyMysqlQueryException->getQuery()."\n";
    echo "PARAMETERS : \n".print_r($easyMysqlQueryException->getBinds())."\n";
    echo 'MESSAGE : ' .$easyMysqlQueryException->getMessage()."\n";
}
