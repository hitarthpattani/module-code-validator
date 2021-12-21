<?php
/**
 * @package     HitarthPattani\CodeValidator
 * @author      Hitarth Pattani <hitarthpattani@gmail.com>
 * @copyright   Copyright © 2021. All rights reserved.
 */
declare(strict_types=1);

namespace HitarthPattani\CodeValidator\Shell;

use HitarthPattani\CodeValidator\App\Logger\Sanitizer;
use HitarthPattani\CodeValidator\Filesystem\SystemList;
use Symfony\Component\Console\Exception\LogicException;

/**
 * @inheritdoc
 */
class Shell implements ShellInterface
{
    /**
     * @var SystemList
     */
    private $systemList;

    /**
     * @var ProcessFactory
     */
    private $processFactory;

    /**
     * @var Sanitizer
     */
    private $sanitizer;

    /**
     * @param SystemList $systemList
     * @param ProcessFactory $processFactory
     * @param Sanitizer $sanitizer
     */
    public function __construct(
        SystemList $systemList,
        ProcessFactory $processFactory,
        Sanitizer $sanitizer
    ) {
        $this->systemList = $systemList;
        $this->processFactory = $processFactory;
        $this->sanitizer = $sanitizer;
    }

    /**
     * If your command contains pipe please use the next construction for correct logging:
     *
     * ``` php
     * $this->shell->execute('/bin/bash -c "set -o pipefail; firstCommand | secondCommand"');
     * ```
     *
     * `commandline` should be always a string as symfony/process package v2.x doesn't support array-type `commandLine`
     *
     * @param array $command
     * @param array $args
     * @return ProcessInterface
     */
    public function execute(array $command, array $args = []): ProcessInterface
    {
        try {
            if ($args) {
                $command = array_merge($command, array_map('escapeshellarg', $args));
            }

            $process = $this->processFactory->create(
                [
                    'command' => $command,
                    'cwd' => $this->systemList->getMagentoRoot(),
                    'timeout' => null
                ]
            );

            $process->execute();
        } catch (ProcessException $e) {
            throw new ShellException(
                $this->sanitizer->sanitize($e->getMessage()),
                $e->getCode()
            );
        }

        return $process;
    }
}
