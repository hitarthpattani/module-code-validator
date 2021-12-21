<?php
/**
 * @package     HitarthPattani\CodeValidator
 * @author      Hitarth Pattani <hitarthpattani@gmail.com>
 * @copyright   Copyright © 2021. All rights reserved.
 */
declare(strict_types=1);

namespace HitarthPattani\CodeValidator\Command;

use HitarthPattani\CodeValidator\Validator\DocDetect;
use HitarthPattani\CodeValidator\Validator\ValidatorInterface;

class RunDocDetect extends AbstractRun
{
    /**
     * @var string
     */
    const NAME = 'run:docdtct';

    /**
     * @return ValidatorInterface
     */
    public function getValidator()
    {
        return $this->pool->getValidator(DocDetect::CODE);
    }

    /**
     * @return string
     */
    public function getCommandName()
    {
        return self::NAME;
    }
}
