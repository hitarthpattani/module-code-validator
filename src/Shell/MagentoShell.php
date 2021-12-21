<?php
/**
 * @package     HitarthPattani\CodeValidator
 * @author      Hitarth Pattani <hitarthpattani@gmail.com>
 * @copyright   Copyright © 2021. All rights reserved.
 */
declare(strict_types=1);

namespace HitarthPattani\CodeValidator\Shell;

/**
 * ./bin/magento shell wrapper.
 */
class MagentoShell
{
    /**
     * @var Shell
     */
    private $shell;

    /**
     * @param Shell $shell
     */
    public function __construct(Shell $shell)
    {
        $this->shell = $shell;
    }

    /**
     * @param string $command
     * @param array $args
     * @return ProcessInterface
     */
    public function execute(string $command, array $args = []): ProcessInterface
    {
        return $this->shell->execute(
            [
                'php ./bin/magento',
                $command,
                '--ansi',
                '--no-interaction'
            ],
            array_filter($args)
        );
    }
}
