<?php
/**
 * @package     HitarthPattani\CodeValidator
 * @author      Hitarth Pattani <hitarthpattani@gmail.com>
 * @copyright   Copyright © 2021. All rights reserved.
 */
declare(strict_types=1);

namespace HitarthPattani\CodeValidator\Validator;

/**
 * Runs the bundled phpcpd against defined module
 */
class MessDetector extends AbstractValidator implements ValidatorInterface
{
    /**
     * Keys of directory configuration.
     */
    const CODE = 'mess_detector';
    const COMMAND = 'vendor/bin/phpmd';

    /**
     * @param array $path
     * @return array
     */
    public function command($path): array
    {
        return [
            $this->path(self::COMMAND),
            $path,
            'text',
            $this->configFileList->getPhpMdConfig(),
            '--exclude',
            'vendor/,templates/'
        ];
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return 'Mess Detector';
    }

    /**
     * @return string
     */
    public function code(): string
    {
        return self::CODE;
    }

    /**
     * @return string
     */
    public function description(): string
    {
        return 'Runs php mess detector against environment configured paths/files';
    }
}
