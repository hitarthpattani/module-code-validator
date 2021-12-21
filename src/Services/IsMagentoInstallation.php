<?php
/**
 * @package     HitarthPattani\CodeValidator
 * @author      Hitarth Pattani <hitarthpattani@gmail.com>
 * @copyright   Copyright © 2021. All rights reserved.
 */
declare(strict_types=1);

namespace HitarthPattani\CodeValidator\Services;

use HitarthPattani\CodeValidator\Filesystem\Driver\File;
use HitarthPattani\CodeValidator\Filesystem\ConfigFileList;
use HitarthPattani\CodeValidator\Filesystem\FileSystemException;

/**
 * Check for magento installation.
 */
class IsMagentoInstallation
{
    /**
     * @var File
     */
    private $file;

    /**
     * @var ConfigFileList
     */
    private $configFileList;

    /**
     * @param File $file
     * @param ConfigFileList $configFileList
     */
    public function __construct(
        File $file,
        ConfigFileList $configFileList
    ) {
        $this->file = $file;
        $this->configFileList = $configFileList;
    }

    /**
     * Check if this is magneto installation or not.
     *
     * @return bool
     * @throws FileSystemException
     */
    public function check(): bool
    {
        return $this->file->isExists($this->configFileList->getConfig());
    }
}
