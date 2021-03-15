<?php
/** @noinspection PhpComposerExtensionStubsInspection */
declare(strict_types=1);


namespace EasyMysql\Entity;


use mysqli_result;

class MysqliResultSet implements ResultSetInterface
{
    private ?mysqli_result $result;

    public function __construct(?mysqli_result $result)
    {
        $this->result = $result;
    }

    public function getResult(): ?mysqli_result
    {
        return $this->result;
    }

     public function freeResult(): void
    {
        if ($this->result instanceof mysqli_result) {
            $this->result->free_result();
        }
    }

    public function closeResult(): void
    {
        if ($this->result instanceof mysqli_result) {
            $this->result->close();
        }
    }
}
