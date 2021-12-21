<?php
/**
 * @package     HitarthPattani\CodeValidator
 * @author      Hitarth Pattani <hitarthpattani@gmail.com>
 * @copyright   Copyright © 2021. All rights reserved.
 */
declare(strict_types=1);

namespace HitarthPattani\CodeValidator\App\Logger;

/**
 * Uses for sanitize sensitive data.
 */
class Sanitizer
{
    /**
     * Array of replacements that will be applied to log messages.
     *
     * @var array
     */
    private $replacements = [
        '/-password=[\'"].*?[\'"](\s|$)/i' => '-password=\'******\'$1',
        '/mysqldump (.* )-p\'[^\']+\'/i'  => 'mysqldump $1-p\'******\'',
    ];

    /**
     * Finds and replace sensitive data in record message.
     *
     * @param string $message
     * @return string
     */
    public function sanitize(string $message): string
    {
        foreach ($this->replacements as $pattern => $replacement) {
            $message = preg_replace($pattern, $replacement, $message);
        }

        return $message;
    }
}
