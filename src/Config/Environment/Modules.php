<?php
/**
 * @package     HitarthPattani\CodeValidator
 * @author      Hitarth Pattani <hitarthpattani@gmail.com>
 * @copyright   Copyright © 2021. All rights reserved.
 */
declare(strict_types=1);

namespace HitarthPattani\CodeValidator\Config\Environment;

use Symfony\Component\Yaml\Exception\ParseException;
use HitarthPattani\CodeValidator\Filesystem\FileSystemException;
use HitarthPattani\CodeValidator\Filesystem\DirectoryList;

/**
 * Reads configuration from .codevalidator.env.yaml to get modules.
 */
class Modules implements ReaderInterface
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * Cached modules
     *
     * @var array|null
     */
    private $modules;

    /**
     * @param Reader $reader
     */
    public function __construct(
        Reader $reader
    ) {
        $this->reader = $reader;
    }

    /**
     * @return array
     * @throws ParseException
     * @throws FileSystemException
     */
    public function read(): array
    {
        if ($this->modules === null) {
            $config = $this->reader->read();

            $this->modules = [];

            if (isset($config[DirectoryList::DIR_CODE])) {
                // phpcs:disable Magento2.Performance.ForeachArrayMerge.ForeachArrayMerge
                $this->modules = array_merge(
                    $this->modules,
                    array_keys($config[DirectoryList::DIR_CODE])
                );
                // phpcs:enable
            }

            if (isset($config[DirectoryList::DIR_DESIGN])) {
                // phpcs:disable Magento2.Performance.ForeachArrayMerge.ForeachArrayMerge
                foreach ($config[DirectoryList::DIR_DESIGN] as $namespaces) {
                    $this->modules = array_merge(
                        $this->modules,
                        array_keys($namespaces)
                    );
                }
                // phpcs:enable
            }

            $this->modules = array_unique($this->modules);
        }

        return $this->modules;
    }
}
