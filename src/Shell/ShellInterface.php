<?php
/**
 * @package     HitarthPattani\CodeValidator
 * @author      Hitarth Pattani <hitarthpattani@gmail.com>
 * @copyright   Copyright © 2021. All rights reserved.
 */
declare(strict_types=1);

namespace HitarthPattani\CodeValidator\Shell;

/**
 * Provides access to system shell operations.
 *
 * @api
 */
interface ShellInterface
{
    /**
     * Runs shell command.
     *
     * @param array $command
     * @param array $args
     * @return ProcessInterface
     * @throws ShellException
     */
    public function execute(array $command, array $args = []): ProcessInterface;
}
