<?php
/**
 * @package     HitarthPattani\CodeValidator
 * @author      Hitarth Pattani <hitarthpattani@gmail.com>
 * @copyright   Copyright © 2021. All rights reserved.
 */
declare(strict_types=1);

namespace HitarthPattani\CodeValidator\Shell;

use Symfony\Component\Console\Formatter\OutputFormatterStyle;

/**
 * Provides new output formatters.
 */
class OutputFormatter
{
    /**
     * @var array
     */
    private $formatters = [
        'title' => ['foreground' => 'white', 'background' => 'cyan', 'options' => ['bold']],
        'output' => ['foreground' => 'white', 'background' => 'default', 'options' => ['bold']],
        'success' => ['foreground' => 'white', 'background' => 'green', 'options' => ['bold']],
        'failure' => ['foreground' => 'white', 'background' => 'red', 'options' => ['bold']],
        'message' => ['foreground' => 'magenta', 'background' => 'default', 'options' => ['bold']],
        'warning' => ['foreground' => 'yellow', 'background' => 'default', 'options' => ['bold']],
    ];

    /**
     * Add Output Formatter Styles to Output
     *
     * @param $output
     * @return void
     * @throws ShellException If command was executed with error
     */
    public function execute($output): void
    {
        foreach ($this->formatters as $style => $formatter) {
            $output->getFormatter()->setStyle(
                $style,
                new OutputFormatterStyle(
                    $formatter['foreground'],
                    $formatter['background'],
                    $formatter['options']
                )
            );
        }
    }
}
