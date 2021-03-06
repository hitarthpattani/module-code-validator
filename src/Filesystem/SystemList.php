<?php
/**
 * @package     HitarthPattani\CodeValidator
 * @author      Hitarth Pattani <hitarthpattani@gmail.com>
 * @copyright   Copyright © 2021. All rights reserved.
 */
declare(strict_types=1);

namespace HitarthPattani\CodeValidator\Filesystem;

/**
 * Simplest directory locator.
 */
class SystemList
{
    /**
     * @var string
     */
    private $root;

    /**
     * @var string
     */
    private $magentoRoot;

    /**
     * @param string $root
     * @param string $magentoRoot
     */
    public function __construct(string $root, string $magentoRoot)
    {
        $this->root = $root;
        $this->magentoRoot = $magentoRoot;
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
     */
    public function getConfig(): string
    {
        return $this->getRoot() . '/config';
    }

    /**
     * @return string
     */
    public function getDist(): string
    {
        return $this->getRoot() . '/dist';
    }
}
