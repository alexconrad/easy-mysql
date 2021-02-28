<?php


namespace EasyMysql;


use DI\ContainerBuilder;

class PhpDi
{
    public static function addDefinitions(ContainerBuilder $containerBuilder): void {
        $containerBuilder->addDefinitions(__DIR__.'/../config/config.php');
    }

}
