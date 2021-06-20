<?php
declare(strict_types=1);

namespace EasyMysql;

use EasyMysql\Exceptions\DuplicateEntryException;
use EasyMysql\Exceptions\EasyMysqlQueryException;
use Generator;

/**
 * Class EasyMysql
 * @package EasyMysql
 */
class EasyMysql
{

    private Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $query
     * @param array $binds
     * @return Entity\ResultSetInterface
     * @throws Exceptions\EasyMysqlQueryException
     */
    public function query(string $query, array $binds = []): Entity\ResultSetInterface
    {
        return $this->config->connection()->query($query, $binds);
    }

    /**
     * @param string $query
     * @param array $binds
     * @throws EasyMysqlQueryException|DuplicateEntryException
     */
    public function dmlQuery(string $query, array $binds = []): void
    {
        $this->config->connection()->dmlQuery($query, $binds);
    }

    /**
     * @param string $query
     * @param array $binds
     * @return array
     * @throws Exceptions\EasyMysqlQueryException
     */
    public function fetchFirstColumn(string $query, array $binds = []): array
    {
        $result = $this->query($query, $binds);
        $ret = [];
        while ($row = $this->config->connection()->fetchAssoc($result)) {
            $ret[] = reset($row);
        }
        return $ret;
    }

    /**
     * @param string $query
     * @param array $binds
     * @return array
     * @throws Exceptions\EasyMysqlQueryException
     */
    public function fetchAllKeyValue(string $query, array $binds = []): array
    {
        $result = $this->query($query, $binds);
        $ret = [];
        while ($row = $this->config->connection()->fetchNum($result)) {
            $ret[$row[0]] = $row[1];
        }
        return $ret;
    }

    /**
     * @param string $query
     * @param array $binds
     * @return array
     * @throws Exceptions\EasyMysqlQueryException
     */
    public function fetchAllAssociative(string $query, array $binds = []): array
    {
        $result = $this->query($query, $binds);
        $ret = [];
        while ($row = $this->config->connection()->fetchAssoc($result)) {
            $ret[] = $row;
        }
        return $ret;
    }

    /**
     * @param string $query
     * @param array $binds
     * @return array
     * @throws Exceptions\EasyMysqlQueryException
     */
    public function fetchAllAssociativeIndexed(string $query, array $binds = []): array
    {
        $result = $this->query($query, $binds);
        $ret = [];
        while ($row = $this->config->connection()->fetchAssoc($result)) {
            $firstColumnValue = array_shift($row);
            $ret[$firstColumnValue] = $row;
        }
        return $ret;
    }

    /**
     * @param string $query
     * @param array $binds
     * @return string|null
     * @throws Exceptions\EasyMysqlQueryException
     */
    public function fetchOne(string $query, array $binds = []): ?string
    {
        $result = $this->query($query, $binds);
        $row = $this->config->connection()->fetchNum($result);
        if ($row !== null) {
            return (string)$row[0];
        }

        return null;
    }

    /**
     * @param string $query
     * @param array $binds
     * @return array|null
     * @throws Exceptions\EasyMysqlQueryException
     */
    public function fetchRow(string $query, array $binds = []): ?array
    {
        $result = $this->query($query, $binds);
        return $this->config->connection()->fetchAssoc($result);
    }

    /**
     * @param string $query
     * @param array $binds
     * @return Generator|null
     * @throws EasyMysqlQueryException
     */
    public function iterateKeyValue(string $query, array $binds = []): ?Generator
    {
        $result = $this->query($query, $binds);
        while ($row = $this->config->connection()->fetchNum($result)) {
            yield $row[0] => $row[1];
        }
    }

    public function iterateAssoc(string $query, array $binds = []): ?Generator
    {
        $result = $this->query($query, $binds);
        while ($row = $this->config->connection()->fetchNum($result)) {
            yield $row;
        }
    }

    /**
     * @param string $query
     * @param array $binds
     * @return int|string
     * @throws EasyMysqlQueryException|DuplicateEntryException
     */
    public function insert(string $query, array $binds = []): int|string
    {
        $this->dmlQuery($query, $binds);
        return $this->config->connection()->lastInsertId();
    }

    /**
     * @param string $query
     * @param array $binds
     * @return int
     * @throws EasyMysqlQueryException|DuplicateEntryException
     */
    public function update(string $query, array $binds = []): int
    {
        $this->dmlQuery($query, $binds);
        return $this->config->connection()->affectedRows();
    }

    /**
     * @param string $query
     * @param array $binds
     * @return int
     * @throws EasyMysqlQueryException|DuplicateEntryException
     */
    public function delete(string $query, array $binds = []): int
    {
        $this->dmlQuery($query, $binds);
        return $this->config->connection()->affectedRows();
    }

    public function lastInsertId()
    {
        return $this->config->connection()->lastInsertId();
    }

    public function affectedRows(): int
    {
        return $this->config->connection()->affectedRows();
    }

}
