<?php
declare(strict_types=1);

namespace EasyMysql\Connection;


use EasyMysql\Entity\ResultSetInterface;
use EasyMysql\Exceptions\EasyMysqlQueryException;

interface ConnectionInterface
{
    /**
     * @param string $query
     * @param array $binds
     * @return ResultSetInterface
     * @throws EasyMysqlQueryException
     */
    public function query(string $query, array $binds = []): ResultSetInterface;
    public function fetchAssoc(ResultSetInterface $result): ?array;
    public function fetchNum(ResultSetInterface $result): ?array;
    public function fetchAll(ResultSetInterface $result): ?array;

}
