<?php
/**
 * @package     HitarthPattani\CodeValidator
 * @author      Hitarth Pattani <hitarthpattani@gmail.com>
 * @copyright   Copyright © 2021. All rights reserved.
 */
declare(strict_types=1);

namespace HitarthPattani\CodeValidator\Filesystem\Reader;

use HitarthPattani\CodeValidator\Filesystem\FileSystemException;

/**
 * Read content of file.
 */
interface ReaderInterface
{
    /**
     * @return array
     * @throws FileSystemException
     */
    public function read(): array;
}
