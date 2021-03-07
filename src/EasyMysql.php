<?php
/** @noinspection PhpComposerExtensionStubsInspection */
declare(strict_types=1);


namespace EasyMysql;


use PDOException;

class EasyMysql
{

    private Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function query($query, $binds = null): Entity\ResultSetInterface
    {
        return $this->config->connection()->query($query, $binds);
    }

    public function column($query, $column, $binds = null): array
    {
        try {
            $result = $this->query($query, $binds);
            $ret = [];
            while ($row = $this->config->connection()->fetchAssoc($result)) {
                $ret[] = $row[$column];
            }
            return $ret;
        } catch (PDOException $e) {
            //TODO
            throw $e;
        }
    }

    public function assoc01(string $query, $binds = null): array
    {
        try {
            $result = $this->query($query, $binds);
            $ret = [];
            while ($row = $this->config->connection()->fetchNum($result)) {
                $ret[$row[0]] = $row[1];
            }
        } catch (PDOException $e) {
            //TODO
            throw $e;
        }
        return $ret;
    }

    public function assocAll(string $query, string $columnKey = null, $binds = null): array
    {
        try {
            $result = $this->query($query, $binds);
            $ret = [];
            while ($row = $this->config->connection()->fetchAssoc($result)) {
                $ret[$row[$columnKey]] = $row;
            }
            return $ret;
        } catch (PDOException $e) {
            //TODO
            throw $e;
        }

    }

    public function row(string $query, array $binds = null): ?array
    {
        try {
            $result = $this->query($query, $binds);
            return $this->config->connection()->fetchAssoc($result);
        } catch (PDOException $e) {
            //TODO
            throw $e;
        }

    }

    public function all(string $query, array $binds = null): array
    {
        try {
            $result = $this->query($query, $binds);
            return $this->config->connection()->fetchAll($result);
        } catch (PDOException $e) {
            //TODO
            throw $e;
        }

    }

}
