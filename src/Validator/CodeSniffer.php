<?php
/**
 * @package     HitarthPattani\CodeValidator
 * @author      Hitarth Pattani <hitarthpattani@gmail.com>
 * @copyright   Copyright © 2021. All rights reserved.
 */
declare(strict_types=1);

namespace HitarthPattani\CodeValidator\Validator;

/**
 * Runs the phpcpd against defined module
 */
class CodeSniffer extends AbstractValidator implements ValidatorInterface
{
    /**
     * Keys of directory configuration.
     */
    const CODE = 'code_sniffer';
    const COMMAND = 'vendor/bin/phpcs';

    /**
     * @param array $path
     * @return array
     */
    public function command($path): array
    {
        return [
            $this->path(self::COMMAND),
            '--standard=' . $this->configFileList->getRulesetConfig(),
            '-s',
            $path,
            '--ignore=vendor/*'
        ];
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return 'Code Sniffer';
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
        return 'Runs the phpcs against environment configured paths / files';
    }
}
