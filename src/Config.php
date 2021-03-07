<?php
/** @noinspection PhpComposerExtensionStubsInspection */
declare(strict_types=1);

namespace EasyMysql;

use EasyMysql\Connection\ConnectionInterface;
use EasyMysql\Connection\MysqliConnection;
use EasyMysql\Connection\PDOConnection;
use EasyMysql\Enum\MysqlDriverEnum;
use mysqli;
use PDO;
use RuntimeException;

class Config
{
    private static ?ConnectionInterface $driver = null;
    private MysqlDriverEnum|PDO|mysqli $mysqlDriver;
    private string $host;
    private string $user;
    private string $pass;
    private ?string $database;
    private int $port;

    public function __construct(PDO|mysqli|MysqlDriverEnum $mysqlDriver, string $host, string $user, string $pass, ?string $database, int $port)
    {
        $this->mysqlDriver = $mysqlDriver;
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->database = $database;
        $this->port = $port;
    }

    public function connection(): ConnectionInterface
    {
        if (self::$driver === null) {
            if ($this->mysqlDriver instanceof mysqli) {
                self::$driver = new MysqliConnection($this->mysqlDriver);
            } elseif ($this->mysqlDriver instanceof PDO) {
                self::$driver = new PDOConnection($this->mysqlDriver);
            } elseif ($this->mysqlDriver->equals(MysqlDriverEnum::MYSQLI())) {
                self::$driver = new MysqliConnection(
                    new mysqli($this->host, $this->user, $this->pass, $this->database, $this->port)
                );
            } elseif ($this->mysqlDriver->equals(MysqlDriverEnum::PDO())) {
                self::$driver = new PDOConnection(
                    new PDO(
                        'mysql:dbname=' . $this->database . ';host=' . $this->host . ';port=' . $this->port,
                        $this->user,
                        $this->pass,
                        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                    )
                );
            } else {
                throw new RuntimeException('Cannot build connection interface');
            }
        }

        return self::$driver;
    }
}
