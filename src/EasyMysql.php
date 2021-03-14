<?php
declare(strict_types=1);


namespace EasyMysql;


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
     * @param $query
     * @param array $binds
     * @return Entity\ResultSetInterface
     * @throws Exceptions\EasyMysqlQueryException
     */
    public function query($query, array $binds = []): Entity\ResultSetInterface
    {
        return $this->config->connection()->query($query, $binds);
    }

    /**
     * @param $query
     * @param array $binds
     * @return array
     * @throws Exceptions\EasyMysqlQueryException
     */
    public function fetchFirstColumn($query, array $binds = []): array
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

}
