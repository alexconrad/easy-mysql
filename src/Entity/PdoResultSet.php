<?php
/** @noinspection PhpComposerExtensionStubsInspection */
declare(strict_types=1);


namespace EasyMysql\Entity;


use PDOStatement;

class PdoResultSet implements ResultSetInterface
{
    private ?PDOStatement $result;

    public function __construct(?PDOStatement $result)
    {
        $this->result = $result;
    }

    public function getResult(): ?PDOStatement
    {
        return $this->result;
    }

    public function freeResult(): void
    {
        if ($this->result instanceof PDOStatement) {
            $this->result->closeCursor();
        }
    }

    public function closeResult(): void
    {
        if ($this->result instanceof PDOStatement) {
            $this->result->closeCursor();
        }
    }
}
