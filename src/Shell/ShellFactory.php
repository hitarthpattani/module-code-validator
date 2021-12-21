<?php
/**
 * @package     HitarthPattani\CodeValidator
 * @author      Hitarth Pattani <hitarthpattani@gmail.com>
 * @copyright   Copyright © 2021. All rights reserved.
 */
declare(strict_types=1);

namespace HitarthPattani\CodeValidator\Shell;

use HitarthPattani\CodeValidator\App\ContainerInterface;

/**
 * Factory class for shell wrappers.
 */
class ShellFactory
{
    const STRATEGY_SHELL = 'shell';
    const STRATEGY_MAGENTO_SHELL = 'magento_shell';

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $strategy
     * @return ShellInterface
     */
    public function create(string $strategy): ShellInterface
    {
        if ($strategy === self::STRATEGY_MAGENTO_SHELL) {
            return $this->createMagento();
        }

        return $this->container->create(Shell::class);
    }

    /**
     * @return MagentoShell
     */
    public function createMagento(): MagentoShell
    {
        return $this->container->create(MagentoShell::class);
    }
}
