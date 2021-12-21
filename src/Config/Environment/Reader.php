<?php
/**
 * @package     HitarthPattani\CodeValidator
 * @author      Hitarth Pattani <hitarthpattani@gmail.com>
 * @copyright   Copyright © 2021. All rights reserved.
 */
declare(strict_types=1);

namespace HitarthPattani\CodeValidator\Config\Environment;

use HitarthPattani\CodeValidator\Filesystem\ConfigFileList;
use HitarthPattani\CodeValidator\Filesystem\FileSystemException;
use HitarthPattani\CodeValidator\Filesystem\Driver\File;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

/**
 * Reads configuration from .codevalidator.env.yaml configuration file.
 */
class Reader implements ReaderInterface
{
    /**
     * @var ConfigFileList
     */
    private $configFileList;

    /**
     * @var File
     */
    private $file;

    /**
     * Cached configuration
     *
     * @var array|null
     */
    private $config;

    /**
     * @param ConfigFileList $configFileList
     * @param File $file
     */
    public function __construct(ConfigFileList $configFileList, File $file)
    {
        $this->configFileList = $configFileList;
        $this->file = $file;
    }

    /**
     * @return array
     * @throws ParseException
     * @throws FileSystemException
     */
    public function read(): array
    {
        if ($this->config === null) {
            $distPath = $this->configFileList->getDistEnvConfig();

            if (!$this->file->isExists($distPath)) {
                $distConfig = [];
            } else {
                $parseFlag = defined(Yaml::class . '::PARSE_CONSTANT') ? Yaml::PARSE_CONSTANT : 0;
                $distConfig = (array)Yaml::parse($this->file->fileGetContents($distPath), $parseFlag);
            }

            $path = $this->configFileList->getEnvConfig();

            if (!$this->file->isExists($path)) {
                $config = [];
            } else {
                $parseFlag = defined(Yaml::class . '::PARSE_CONSTANT') ? Yaml::PARSE_CONSTANT : 0;
                $config = (array)Yaml::parse($this->file->fileGetContents($path), $parseFlag);
            }

            $this->config = array_replace(
                $distConfig,
                $config
            );
        }

        return $this->config;
    }
}
