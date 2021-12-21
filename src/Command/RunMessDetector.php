<?php
/**
 * @package     HitarthPattani\CodeValidator
 * @author      Hitarth Pattani <hitarthpattani@gmail.com>
 * @copyright   Copyright © 2021. All rights reserved.
 */
declare(strict_types=1);

namespace HitarthPattani\CodeValidator\Command;

use HitarthPattani\CodeValidator\Validator\MessDetector;
use HitarthPattani\CodeValidator\Validator\ValidatorInterface;

class RunMessDetector extends AbstractRun
{
    /**
     * @var string
     */
    const NAME = 'run:phpmd';

    /**
     * @return ValidatorInterface
     */
    public function getValidator()
    {
        return $this->pool->getValidator(MessDetector::CODE);
    }

    /**
     * @return string
     */
    public function getCommandName()
    {
        return self::NAME;
    }
}
