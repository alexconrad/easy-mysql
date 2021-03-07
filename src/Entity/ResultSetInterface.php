<?php
declare(strict_types=1);


namespace EasyMysql\Entity;


interface ResultSetInterface
{
    public function getResult();
    public function freeResult();
    public function closeResult();
}
