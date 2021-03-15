<?php
/** @noinspection PhpUnusedPrivateFieldInspection */
declare(strict_types=1);

namespace EasyMysql\Enum;

use MyCLabs\Enum\Enum;

/**
 * Class MysqlDriver
 * @package EasyMysql\Enum
 * @method static MYSQLI()
 * @method static PDO()
 */
class MysqlDriverEnum extends Enum
{
    private const MYSQLI  = 'MYSQLI';
    private const PDO = 'PDO';
}
