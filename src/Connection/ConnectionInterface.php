<?php
declare(strict_types=1);

namespace EasyMysql\Connection;


use EasyMysql\Entity\ResultSetInterface;

interface ConnectionInterface
{
    public function query(string $query, array $binds = null): ResultSetInterface;
    public function fetchAssoc(ResultSetInterface $result): ?array;
    public function fetchNum(ResultSetInterface $result): ?array;
    public function fetchAll(ResultSetInterface $result): ?array;

}
