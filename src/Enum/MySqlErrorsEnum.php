<?php
/** @noinspection PhpUnusedPrivateFieldInspection */
declare(strict_types=1);

namespace EasyMysql\Enum;

use MyCLabs\Enum\Enum;

/**
 * Class MySqlErrorsEnum
 * @package EasyMysql\Enum
 *
 * @method static MySqlErrorsEnum DUPLICATE_ENTRY()
 */
class MySqlErrorsEnum extends Enum
{
    private const DUPLICATE_ENTRY = 1062;

}
