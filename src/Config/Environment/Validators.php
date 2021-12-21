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
 * Reads configuration from .codevalidator.env.yaml to get config.
 */
class Validators
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * Cached config
     *
     * @var array|null
     */
    private $config;

    /**
     * @param Reader $reader
     */
    public function __construct(
        Reader $reader
    ) {
        $this->reader = $reader;
    }

    /**
     * @param string $code
     * @return array
     * @throws ParseException
     * @throws FileSystemException
     */
    public function read($code): array
    {
        if ($this->config === null) {
            $this->config = $this->reader->read();
        }

        $config = [];

        if (isset($this->config[$code])) {
            $config = $this->config[$code];
        }

        return $config;
    }
}
