<?php
/**
 * @package     HitarthPattani\CodeValidator
 * @author      Hitarth Pattani <hitarthpattani@gmail.com>
 * @copyright   Copyright © 2021. All rights reserved.
 */
declare(strict_types=1);

namespace HitarthPattani\CodeValidator\Shell;

/**
 * Creates instance of ProcessInterface
 */
class ProcessFactory
{
    /**
     * Creates instance of Process
     *
     * @param array $params
     * @return Process|ProcessInterface
     */
    public function create(array $params): ProcessInterface
    {
        return new Process(
            $params['command'],
            $params['cwd'],
            null,
            null,
            $params['timeout']
        );
    }
}
