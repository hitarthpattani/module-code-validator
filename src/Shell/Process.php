<?php
/**
 * @package     HitarthPattani\CodeValidator
 * @author      Hitarth Pattani <hitarthpattani@gmail.com>
 * @copyright   Copyright © 2021. All rights reserved.
 */
declare(strict_types=1);

namespace HitarthPattani\CodeValidator\Shell;

use Symfony\Component\Process\Exception\ProcessFailedException;

/**
 * Runs console commands.
 *
 * @codeCoverageIgnore
 */
class Process extends \Symfony\Component\Process\Process implements ProcessInterface
{
    /**
     * Trim new lines from command output
     *
     * @return string
     */
    public function getOutput(): string
    {
        return trim(parent::getOutput(), PHP_EOL);
    }

    /**
     * @return void
     * @throws ProcessException
     */
    public function execute(): void
    {
        try {
            $this->mustRun();
        } catch (ProcessFailedException $e) {
            $process = $e->getProcess();

            $error = sprintf(
                "The command \"%s\" failed.\n\n%s",
                $process->getCommandLine(),
                trim($process->getErrorOutput() ?: $process->getOutput(), "\n")
            );

            throw new ProcessException($error, $process->getExitCode());
        }
    }
}
