<?php
/** @noinspection PhpComposerExtensionStubsInspection */
declare(strict_types=1);

namespace EasyMysql\Connection;

use EasyMysql\Entity\PdoResultSet;
use EasyMysql\Entity\ResultSetInterface;
use PDO;

class PDOConnection implements ConnectionInterface
{

    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function query(string $query, array $binds = null): ResultSetInterface
    {
        if ($binds === null) {
            return new PdoResultSet($this->pdo->query($query));
        }
        $preparedStatement = $this->pdo->prepare($query);
        $preparedStatement->execute($binds);
        return new PdoResultSet($preparedStatement);
    }

    public function fetchAssoc(ResultSetInterface $result): ?array
    {
        $assocRow = $result->getResult()->fetch(PDO::FETCH_ASSOC);
        if ($assocRow === false) {
            return null;
        }
        return $assocRow;
    }

    public function fetchNum(ResultSetInterface $result): ?array
    {
        $numRow = $result->getResult()->fetch(PDO::FETCH_NUM);
        if ($numRow === false) {
            return null;
        }
        return $numRow;
    }

    public function fetchAll(ResultSetInterface $result): ?array
    {
        $allRows = $result->getResult()->fetchAll(PDO::FETCH_ASSOC);
        if ($allRows === false) {
            return null;
        }
        return $allRows;
    }


}
