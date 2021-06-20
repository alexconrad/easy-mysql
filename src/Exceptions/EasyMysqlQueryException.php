<?php
declare(strict_types=1);

namespace EasyMysql\Exceptions;


use Exception;
use Throwable;

class EasyMysqlQueryException extends Exception
{
    private string $query;
    private array $binds;

    public function __construct(string $query, array $binds = [], $message = '', $code = 0, Throwable $previous = null)
    {
        $this->query = $query;
        $this->binds = $binds;
        parent::__construct($message, $code, $previous);
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function getBinds(): array
    {
        return $this->binds;
    }

}
