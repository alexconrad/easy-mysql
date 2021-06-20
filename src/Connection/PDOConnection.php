<?php
/** @noinspection PhpComposerExtensionStubsInspection */
declare(strict_types=1);

namespace EasyMysql\Connection;

use EasyMysql\Entity\PdoResultSet;
use EasyMysql\Entity\ResultSetInterface;
use EasyMysql\Enum\MySqlErrorsEnum;
use EasyMysql\Exceptions\DuplicateEntryException;
use EasyMysql\Exceptions\EasyMysqlQueryException;
use PDO;
use PDOException;
use PDOStatement;

class PDOConnection implements ConnectionInterface
{

    private PDO $pdo;

    private int $affectedRows = 0;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param string $query
     * @param array $binds
     * @return ResultSetInterface
     * @throws EasyMysqlQueryException
     */
    public function query(string $query, array $binds = []): ResultSetInterface
    {
        try {
            if (count($binds) === 0) {
                return new PdoResultSet($this->pdo->query($query));
            }
            $preparedStatement = $this->pdo->prepare($query);
            $preparedStatement->execute($binds);
            return new PdoResultSet($preparedStatement);
        } catch (PDOException $e) {
            throw new EasyMysqlQueryException($query, $binds,'#' . $e->getCode() . ': ' . $e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    /**
     * @param string $query
     * @param array $binds
     * @throws EasyMysqlQueryException|DuplicateEntryException
     */
    public function dmlQuery( string $query, array $binds = []): void
    {
        try {
            if (count($binds) === 0) {
                $this->affectedRows = (int)$this->pdo->exec($query);
            } else {
                $preparedStatement = $this->pdo->prepare($query);
                $preparedStatement->execute($binds);
                $this->affectedRows = $preparedStatement->rowCount();
            }
        } catch (PDOException $e) {
            /** @see https://www.php.net/manual/ro/pdo.errorinfo.php */
            if ($e->errorInfo[1] === MySqlErrorsEnum::DUPLICATE_ENTRY()->getValue()) {
                throw new DuplicateEntryException($query, $binds, '#' . $e->getCode() . ': ' . $e->getMessage(), (int)$e->errorInfo[1], $e);
            }
            throw new EasyMysqlQueryException($query, $binds, '#' . $e->getCode() . ': ' . $e->getMessage(), (int)$e->errorInfo[1], $e);
        }
    }

    public function fetchAssoc(ResultSetInterface $result): ?array
    {
        /** @var PdoResultSet $result */
        /** @noinspection NullPointerExceptionInspection */
        $assocRow = $result->getResult()?->fetch(PDO::FETCH_ASSOC);
        if ($assocRow === false) {
            return null;
        }
        return $assocRow;
    }

    public function fetchNum(ResultSetInterface $result): ?array
    {
        /** @var PdoResultSet $result */
        /** @noinspection NullPointerExceptionInspection */
        $numRow = $result->getResult()?->fetch(PDO::FETCH_NUM);
        if ($numRow === false) {
            return null;
        }
        return $numRow;
    }

    public function fetchAll(ResultSetInterface $result): array
    {
        /** @var PdoResultSet $result */
        $pdoResult = $result->getResult();
        if ($pdoResult instanceof PDOStatement) {
            return $pdoResult->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    /** @noinspection ReturnTypeCanBeDeclaredInspection */
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    public function affectedRows(): int
    {
        return $this->affectedRows;
    }


}
