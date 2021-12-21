<?php
/**
 * @package     HitarthPattani\CodeValidator
 * @author      Hitarth Pattani <hitarthpattani@gmail.com>
 * @copyright   Copyright © 2021. All rights reserved.
 */
declare(strict_types=1);

namespace HitarthPattani\CodeValidator\Filesystem;

/**
 * Directory path configurations.
 */
class DirectoryList
{
    /**
     * Keys of directory configuration.
     */
    private const PATH = 'path';

    /**
     * Directory codes.
     */
    public const DIR_CODE = 'code';
    public const DIR_DESIGN = 'design';
    public const DIR_SELF = 'self';

    /**
     * @var string
     */
    private $root;

    /**
     * @var string
     */
    private $magentoRoot;

    /**
     * @param SystemList $systemList
     */
    public function __construct(SystemList $systemList)
    {
        $this->root = $systemList->getRoot();
        $this->magentoRoot = $systemList->getMagentoRoot();
    }

    /**
     * Gets a filesystem path of a directory.
     *
     * @param string $code
     * @param bool $relativePath
     * @return string
     * @throws RuntimeException
     */
    public function getPath(string $code, bool $relativePath = false): string
    {
        $magentoRoot = $relativePath ? '' : $this->getMagentoRoot();
        $directories = $this->getDefaultDirectories();

        if (!array_key_exists($code, $directories)) {
            throw  new \RuntimeException("Code {$code} is not registered");
        }

        if (!array_key_exists(static::PATH, $directories[$code])) {
            throw new \RuntimeException(
                sprintf('Config var "%s" does not exists', static::PATH)
            );
        }

        $path = $directories[$code][self::PATH];

        return $magentoRoot . ($magentoRoot && $path ? '/' : '') . $path;
    }

    /**
     * @return string
     */
    public function getRoot(): string
    {
        return $this->root;
    }

    /**
     * @return string
     */
    public function getMagentoRoot(): string
    {
        return $this->magentoRoot;
    }

    /**
     * @return string
     * @throws RuntimeException
     */
    public function getCode(): string
    {
        return $this->getPath(static::DIR_CODE);
    }

    /**
     * @return string
     * @throws RuntimeException
     */
    public function getDesign(): string
    {
        return $this->getPath(static::DIR_DESIGN);
    }

    /**
     * @return array
     */
    private function getDefaultDirectories(): array
    {
        $config = [
            static::DIR_CODE => [static::PATH => 'app/code'],
            static::DIR_DESIGN => [static::PATH => 'app/design']
        ];

        return $config;
    }
}
