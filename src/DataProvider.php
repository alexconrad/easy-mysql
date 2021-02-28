<?php


namespace EasyMysql;


use EasyMysql\Connection\ConnectionInterface;


class DataProvider
{

    /** @var ConnectionInterface */
    private ConnectionInterface $connection;

    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    public function column($query, $column, $binds = null)
    {
        $result = $this->connection->query($query, $binds);
        $ret = [];
        while ($row = $this->connection->fetch($result)) {
            $ret[] = $row[$column];
        }
        return $ret;
    }

    public function assoc(string $query, string $columnKey, string|array $columntValue, $binds = null)
    {
        $result = $this->connection->query($query, $binds);
        $ret = [];
        while ($row = $this->connection->fetch($result)) {
            if (is_array($columntValue)) {
                if (empty($columntValue)) {
                    $ret[$row[$columnKey]] = $row;
                } else {
                    $items = [];
                    foreach ($columntValue as $item) {
                        $items[$item] = $row[$item];
                    }
                    $ret[$row[$columnKey]] = $items;
                }
            } else {
                $ret[$row[$columnKey]] = $row[$columntValue];
            }
        }
        return $ret;
    }

    public function row(string $query, array $binds = null): ?array
    {
        $result = $this->connection->query($query, $binds);
        return $this->connection->fetch($result);
    }

    public function all(string $query, array $binds = null): array
    {
        $result = $this->connection->query($query, $binds);
        $ret = [];
        while ($row = $this->connection->fetch($result)) {
            $ret[] = $row;
        }
        return $ret;
    }

}
