<?php
/**
 * @package     HitarthPattani\CodeValidator
 * @author      Hitarth Pattani <hitarthpattani@gmail.com>
 * @copyright   Copyright © 2021. All rights reserved.
 */
declare(strict_types=1);

namespace HitarthPattani\CodeValidator;

use Composer\Composer;
use HitarthPattani\CodeValidator\App\ContainerInterface;
use HitarthPattani\CodeValidator\App\Container;
use HitarthPattani\CodeValidator\Command;

/**
 * @inheritdoc
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Application extends \Symfony\Component\Console\Application
{
    /**
     * @var ContainerInterface|Container
     */
    private $container;

    /**
     * @param ContainerInterface|Container $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        parent::__construct(
            $container->create(Composer::class)->getPackage()->getPrettyName(),
            $container->create(Composer::class)->getPackage()->getPrettyVersion()
        );
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * @return void
     */
    protected function getDefaultCommands()
    {
        return array_merge(
            parent::getDefaultCommands(),
            [
                $this->container->create(Command\RunAll::class),
                $this->container->create(Command\RunCodeSniffer::class),
                $this->container->create(Command\RunCopyPaste::class),
                $this->container->create(Command\RunDocDetect::class),
                $this->container->create(Command\RunHeaderComments::class),
                $this->container->create(Command\RunMessDetector::class),
                $this->container->create(Command\RunConfig::class),
                $this->container->create(Command\RunPath::class)
            ]
        );
    }
}
