<?php
/** @noinspection PhpComposerExtensionStubsInspection */
declare(strict_types=1);

namespace EasyMysql\Connection;

use EasyMysql\Entity\MysqliResultSet;
use EasyMysql\Entity\ResultSetInterface;
use EasyMysql\Exceptions\PrepareFailedException;
use EasyMysql\Exceptions\EasyMysqlQueryException;
use mysqli;
use mysqli_sql_exception;

class MysqliConnection implements ConnectionInterface
{
    private mysqli $mysqli;

    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    /**
     * @param string $query
     * @param array $binds
     * @return MysqliResultSet
     * @throws PrepareFailedException|EasyMysqlQueryException
     */
    public function query(string $query, array $binds = []): MysqliResultSet
    {
        try {
            if (count($binds) === 0) {
                $result = mysqli_query($this->mysqli, $query);
                return new MysqliResultSet($result);
            }

            $stmt = mysqli_prepare($this->mysqli, $query);
            if ($stmt === false) {
                throw new PrepareFailedException(mysqli_error($this->mysqli));
            }
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

            mysqli_stmt_bind_param($stmt, $types, ...$binds);
            $result = $stmt->execute() ? $stmt->get_result() : null;
            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            throw new EasyMysqlQueryException($e->getMessage(), $e->getCode(), $e);
        }

        return new MysqliResultSet($result);
    }

    public function fetchAssoc(ResultSetInterface $result): ?array
    {
        return mysqli_fetch_assoc($result->getResult());
    }

    public function fetchNum(ResultSetInterface $result): ?array
    {
        return mysqli_fetch_array($result->getResult(), MYSQLI_NUM);
    }

    public function fetchAll(ResultSetInterface $result): ?array
    {
        return mysqli_fetch_all($result->getResult(), MYSQLI_ASSOC);
    }


}
