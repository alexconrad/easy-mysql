<?php /** @noinspection PhpComposerExtensionStubsInspection */

namespace EasyMysql\Connection;


use EasyMysql\Config;
use EasyMysql\Exceptions\PrepareFailedException;
use mysqli;
use mysqli_result;

class MysqliConnection implements ConnectionInterface
{
    private $link;

    /** @var Config */
    private Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function connect()
    {
        if ($this->link instanceof mysqli) {
            return;
        }

        $this->link = mysqli_connect(
            $this->config->getHost(),
            $this->config->getUser(),
            $this->config->getPass(),
            $this->config->getDatabaseName(),
            $this->config->getPort()
        );
    }

    public function query($query, array $binds = null): ?mysqli_result
    {
        $this->connect();
        if ($binds === null) {
            $result = mysqli_query($this->link, $query);
            return ($result === false) ? null : $result;
        }

        $stmt = mysqli_prepare($this->link, $query);
        if ($stmt === false) {
            throw new PrepareFailedException(mysqli_error($this->link));
        }
        $types = '';
        foreach ($binds as $param) {
            if (is_float($param)) {
                $types .= 'd';
            }
            elseif (is_int($param)) {
                $types .= 'i';
            }
            elseif (is_string($param)) {
                $types .= 's';
            }
            else {
                $types .= 'b';
            }
        }

        mysqli_stmt_bind_param($stmt, $types, ...$binds);

        $result = $stmt->execute() ? $stmt->get_result() : null;

        $stmt->close();

        return $result;
    }

    public function fetch($result): ?array
    {
        return mysqli_fetch_assoc($result);
    }


}
