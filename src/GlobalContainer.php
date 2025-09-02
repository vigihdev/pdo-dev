<?php

declare(strict_types=1);

namespace Vigihdev\PdoDev;

use Symfony\Component\DependencyInjection\ContainerInterface;


class GlobalContainer
{
    private static ?ContainerInterface $container = null;

    public static function setContainer(ContainerInterface $container): void
    {
        self::$container = $container;
    }

    public static function getContainer(): ContainerInterface
    {
        if (self::$container === null) {
            throw new \RuntimeException('Container has not been set yet');
        }

        return self::$container;
    }

    public static function get(string $id): object
    {
        return self::getContainer()->get($id);
    }

    public static function getParameter(string $name): mixed
    {
        return self::getContainer()->getParameter($name);
    }

    public static function has(string $id): bool
    {
        return self::getContainer()->has($id);
    }
}
