<?php
/**
 * @package     HitarthPattani\CodeValidator
 * @author      Hitarth Pattani <hitarthpattani@gmail.com>
 * @copyright   Copyright © 2021. All rights reserved.
 */
declare(strict_types=1);

namespace HitarthPattani\CodeValidator\Filesystem;

/**
 * Resolver of file configurations.
 */
class ConfigFileList
{
    /**
     * @var SystemList
     */
    private $systemList;

    /**
     * @param SystemList $systemList
     */
    public function __construct(SystemList $systemList)
    {
        $this->systemList = $systemList;
    }

    /**
     * @return string
     */
    public function getEnvConfig(): string
    {
        return $this->systemList->getMagentoRoot() . '/.codevalidator.env.yaml';
    }

    /**
     * @return string
     */
    public function getDistEnvConfig(): string
    {
        return $this->systemList->getDist() . '/.codevalidator.env.yaml';
    }

    /**
     * @return string
     */
    public function getPhpMdConfig(): string
    {
        return $this->systemList->getConfig() . '/phpmd.xml';
    }

    /**
     * @return string
     */
    public function getRulesetConfig(): string
    {
        return $this->systemList->getConfig() . '/ruleset.xml';
    }

    /**
     * @return string
     */
    public function getConfig(): string
    {
        return $this->systemList->getMagentoRoot() . '/app/etc/config.php';
    }

    /**
     * @return string
     */
    public function getRegistration(): string
    {
        return $this->systemList->getMagentoRoot() . '/registration.php';
    }
}
