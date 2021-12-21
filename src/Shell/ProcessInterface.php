<?php
/**
 * @package     HitarthPattani\CodeValidator
 * @author      Hitarth Pattani <hitarthpattani@gmail.com>
 * @copyright   Copyright © 2021. All rights reserved.
 */
declare(strict_types=1);

namespace HitarthPattani\CodeValidator\Shell;

/**
 * Process for executing shell commands.
 */
interface ProcessInterface
{
    /**
     * Returns command exit code.
     *
     * @return int
     */
    public function getExitCode();

    /**
     * Returns command output.
     *
     * @return string
     */
    public function getOutput();

    /**
     * Gets the command line to be executed.
     *
     * @return string
     */
    public function getCommandLine();

    /**
     * Runs the process.
     *
     * @return void
     * @throws ProcessException
     */
    public function execute();
}
