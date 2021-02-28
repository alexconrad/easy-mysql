<?php /** @noinspection PhpUnusedPrivateFieldInspection */

namespace EasyMysql\Enum;

use MyCLabs\Enum\Enum;

/**
 * Class MysqlDriver
 * @package EasyMysql\Enum
 * @method static MYSQLI()
 * @method static PDO()
 */
class MysqlDriver extends Enum
{
    private const MYSQLI  = 1;
    private const PDO = 2;
}
