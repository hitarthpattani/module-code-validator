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
class CopyPaste extends AbstractValidator implements ValidatorInterface
{
    /**
     * Keys of directory configuration.
     */
    const CODE = 'copy_paste';
    const COMMAND = 'vendor/bin/phpcpd';

    /**
     * @var string
     */
    const KEY_FILES_EXCLUDES = 'files-excludes';

    /**
     * @param array $path
     * @return array
     */
    public function command($path): array
    {
        $config = $this->getConfig();

        $params = ['--exclude=vendor'];
        if (isset($config[self::KEY_FILES_EXCLUDES])) {
            $params[] = '--names-exclude=' . implode(",", $config[self::KEY_FILES_EXCLUDES]);
        }

        return array_merge(
            [
                $this->path(self::COMMAND),
                '--fuzzy',
                $path
            ],
            $params
        );
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return 'Copy/Paste Detector';
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
        return 'Runs phpcpd against environment configured paths/files';
    }
}
