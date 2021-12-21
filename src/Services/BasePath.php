<?php
/**
 * @package     HitarthPattani\CodeValidator
 * @author      Hitarth Pattani <hitarthpattani@gmail.com>
 * @copyright   Copyright © 2021. All rights reserved.
 */
declare(strict_types=1);

namespace HitarthPattani\CodeValidator\Services;

use HitarthPattani\CodeValidator\Filesystem\FileSystemException;
use HitarthPattani\CodeValidator\Filesystem\SystemList;

/**
 * Get the base path for execution and testing.
 */
class BasePath
{
    /**
     * @var SystemList
     */
    private $systemList;

    /**
     * @var IsStandaloneModule
     */
    private $isStandaloneModule;

    /**
     * @var IsMagentoInstallation
     */
    private $isMagentoInstallation;

    /**
     * @param SystemList $systemList
     * @param IsStandaloneModule $isStandaloneModule
     * @param IsMagentoInstallation $isMagentoInstallation
     */
    public function __construct(
        SystemList $systemList,
        IsStandaloneModule $isStandaloneModule,
        IsMagentoInstallation $isMagentoInstallation
    ) {
        $this->systemList = $systemList;
        $this->isStandaloneModule = $isStandaloneModule;
        $this->isMagentoInstallation = $isMagentoInstallation;
    }

    /**
     * Get the installation path
     *
     * @return string
     * @throws FileSystemException
     */
    public function get(): string
    {
        if ($this->isMagentoInstallation->check() || $this->isStandaloneModule->check()) {
            return $this->systemList->getMagentoRoot();
        }

        return $this->systemList->getRoot();
    }
}
