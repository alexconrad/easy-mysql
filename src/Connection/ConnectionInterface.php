<?php

namespace EasyMysql\Connection;


interface ConnectionInterface
{

    public function connect();
    public function query(string $query, array $binds = null);
    public function fetch($result): ?array;

}
