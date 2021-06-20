<?php
/** @noinspection PhpComposerExtensionStubsInspection */
declare(strict_types=1);

namespace EasyMysql\Connection;

use EasyMysql\Entity\MysqliResultSet;
use EasyMysql\Entity\ResultSetInterface;
use EasyMysql\Enum\MySqlErrorsEnum;
use EasyMysql\Exceptions\DuplicateEntryException;
use EasyMysql\Exceptions\EasyMysqlQueryException;
use JetBrains\PhpStorm\Pure;
use mysqli;
use mysqli_sql_exception;

class MysqliConnection implements ConnectionInterface
{
    private mysqli $mysqli;

    private int $affectedRows;

    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    /**
     * @param string $query
     * @param array $binds
     * @return MysqliResultSet
     * @throws EasyMysqlQueryException
     */
    public function query(string $query, array $binds = []): MysqliResultSet
    {
        try {
            if (count($binds) === 0) {
                $result = mysqli_query($this->mysqli, $query);
                if (is_bool($result)) {
                    $result = null;
                }
                return new MysqliResultSet($result);
            }

            $this->checkIfNamedParameters($query, $binds);

            $stmt = mysqli_prepare($this->mysqli, $query);
            if ($stmt === false) {
                throw new EasyMysqlQueryException($query, $binds, mysqli_error($this->mysqli));
            }
            $types = $this->getBindTypes($binds);

            mysqli_stmt_bind_param($stmt, $types, ...$binds);
            $result = $stmt->execute() ? $stmt->get_result() : null;
            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            throw new EasyMysqlQueryException($e->getMessage(), $binds,'#'.$e->getCode(), $e->getCode(), $e);
        }

        return new MysqliResultSet($result);
    }

    /**
     * @param string $query
     * @param array $binds
     * @throws EasyMysqlQueryException|DuplicateEntryException
     */
    public function dmlQuery(string $query, array $binds = []): void
    {
        try {
            if (count($binds) === 0) {
                /** @noinspection UnusedFunctionResultInspection */
                mysqli_query($this->mysqli, $query);
                $this->affectedRows = $this->mysqli->affected_rows;
            } else {

                $this->checkIfNamedParameters($query, $binds);

                $stmt = mysqli_prepare($this->mysqli, $query);
                if ($stmt === false) {
                    throw new EasyMysqlQueryException($query, $binds, mysqli_error($this->mysqli));
                }
                $types = $this->getBindTypes($binds);

                mysqli_stmt_bind_param($stmt, $types, ...$binds);
                $stmt->execute();
                $this->affectedRows = $this->mysqli->affected_rows;
                $stmt->close();
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() === MySqlErrorsEnum::DUPLICATE_ENTRY()->getValue()) {
                throw new DuplicateEntryException($query, $binds, $e->getMessage(), $e->getCode(), $e);
            }
            throw new EasyMysqlQueryException($query, $binds, $e->getMessage(), $e->getCode(), $e);
        }
    }

    public function lastInsertId()
    {
        return $this->mysqli->insert_id;
    }

    public function affectedRows(): int
    {
        return $this->affectedRows;
    }

    public function fetchAssoc(ResultSetInterface $result): ?array
    {
        /** @var MysqliResultSet $result */
        return mysqli_fetch_assoc($result->getResult());
    }

    public function fetchNum(ResultSetInterface $result): ?array
    {
        /** @var MysqliResultSet $result */
        return mysqli_fetch_array($result->getResult(), MYSQLI_NUM);
    }

    public function fetchAll(ResultSetInterface $result): array
    {
        /** @var MysqliResultSet $result */
        return mysqli_fetch_all($result->getResult(), MYSQLI_ASSOC);
    }


    /**
     * @param array $binds
     * @return string
     */
    #[Pure] private function getBindTypes(array $binds): string
    {
        $types = '';
        foreach ($binds as $param) {
            if (is_float($param)) {
                $types .= 'd';
            } elseif (is_int($param)) {
                $types .= 'i';
            } elseif (is_string($param)) {
                $types .= 's';
            } else {
                $types .= 'b';
            }
        }

        return $types;
    }

    /**
     * @param string $query
     * @param array $binds
     * @throws EasyMysqlQueryException
     */
    private function checkIfNamedParameters(string $query, array $binds): void
    {
        if (!isset($binds[0])) {
            //TODO add config flag to enable converting them
            throw new EasyMysqlQueryException($query, $binds, 'Named parameters are not supported in MYSQLi.');
        }
    }

}
