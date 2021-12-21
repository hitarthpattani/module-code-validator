<?php
/**
 * @package     HitarthPattani\CodeValidator
 * @author      Hitarth Pattani <hitarthpattani@gmail.com>
 * @copyright   Copyright © 2021. All rights reserved.
 */
declare(strict_types=1);

namespace HitarthPattani\CodeValidator\Command;

use HitarthPattani\CodeValidator\Validator\CopyPaste;
use HitarthPattani\CodeValidator\Validator\ValidatorInterface;

class RunCopyPaste extends AbstractRun
{
    /**
     * @var string
     */
    const NAME = 'run:phpcpd';

    /**
     * @return ValidatorInterface
     */
    public function getValidator()
    {
        return $this->pool->getValidator(CopyPaste::CODE);
    }

    /**
     * @return string
     */
    public function getCommandName()
    {
        return self::NAME;
    }
}
