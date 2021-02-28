<?php

namespace EasyMysql\Connection;


class PDOConnection implements ConnectionInterface
{

    public function __construct()
    {

    }

    public function connect()
    {
        // TODO: Implement connect() method.
    }

    public function query(string $query, array $binds = null)
    {
        // TODO: Implement query() method.
    }

    public function fetch($result): ?array
    {
        // TODO: Implement fetch() method.
    }


}
