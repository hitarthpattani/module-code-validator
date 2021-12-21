<?php
/**
 * @package     HitarthPattani\CodeValidator
 * @author      Hitarth Pattani <hitarthpattani@gmail.com>
 * @copyright   Copyright © 2021. All rights reserved.
 */
declare(strict_types=1);

namespace HitarthPattani\CodeValidator;

use LogicException;

/**
 * @codeCoverageIgnore
 */
class ExtensionRegistrar
{
    /**
     * @var array
     */
    private static $paths = [];

    /**
     * Sets the location of an extension.
     *
     * @param string $componentName
     * @param string $path
     * @return void
     * @throws LogicException
     * phpcs:disable Magento2.Functions.StaticFunction
     */
    public static function register($componentName, $path): void
    {
        if (isset(self::$paths[$componentName])) {
            throw new LogicException(
                sprintf(
                    '%s from %s has been already defined in %s',
                    $componentName,
                    $path,
                    self::$paths[$componentName]
                )
            );
        }

        self::$paths[$componentName] = str_replace('\\', '/', $path);
    }

    /**
     * Returns array of registered extensions
     *
     * @return array
     * phpcs:disable Magento2.Functions.StaticFunction
     */
    public static function getPaths(): array
    {
        return self::$paths;
    }

    /**
     * Returns extension path by given extension name
     *
     * @param string $componentName
     * @return string|null
     * phpcs:disable Magento2.Functions.StaticFunction
     */
    public static function getPath(string $componentName): ?string
    {
        return self::$paths[$componentName] ?? null;
    }
}
