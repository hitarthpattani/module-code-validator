<?php
/**
 * @package     HitarthPattani\CodeValidator
 * @author      Hitarth Pattani <hitarthpattani@gmail.com>
 * @copyright   Copyright © 2021. All rights reserved.
 */
declare(strict_types=1);

namespace HitarthPattani\CodeValidator\Command;

use HitarthPattani\CodeValidator\Validator\HeaderComments;
use HitarthPattani\CodeValidator\Validator\ValidatorInterface;

class RunHeaderComments extends AbstractRun
{
    /**
     * @var string
     */
    const NAME = 'run:hdcomm';

    /**
     * @return ValidatorInterface
     */
    public function getValidator()
    {
        return $this->pool->getValidator(HeaderComments::CODE);
    }

    /**
     * @return string
     */
    public function getCommandName()
    {
        return self::NAME;
    }
}
