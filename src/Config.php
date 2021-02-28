<?php


namespace EasyMysql;

use EasyMysql\Enum\MysqlDriver;

class Config
{

    /** @var Enum\MysqlDriver */
    private Enum\MysqlDriver $mysqlDriver;
    private string $host;
    private int $port;
    private string $user;
    private string $pass;
    private ?string $databaseName;
    private $extra;

    public function __construct(MysqlDriver $mysqlDriver, string $host, int $port, string $user, string $pass, ?string $databaseName, $extra)
    {
        $this->mysqlDriver = $mysqlDriver;
        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->pass = $pass;
        $this->databaseName = $databaseName;
        $this->extra = $extra;
    }

    /**
     * @return Enum\MysqlDriver
     */
    public function getMysqlDriver(): Enum\MysqlDriver
    {
        return $this->mysqlDriver;
    }



    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getPass(): string
    {
        return $this->pass;
    }

    public function getDatabaseName(): ?string
    {
        return $this->databaseName;
    }

    /**
     * @return mixed
     */
    public function getExtra()
    {
        return $this->extra;
    }



}
