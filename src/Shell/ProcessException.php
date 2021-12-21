<?php
/**
 * @package     HitarthPattani\CodeValidator
 * @author      Hitarth Pattani <hitarthpattani@gmail.com>
 * @copyright   Copyright © 2021. All rights reserved.
 */
declare(strict_types=1);

namespace HitarthPattani\CodeValidator\Shell;

use HitarthPattani\CodeValidator\App\GenericException;

/**
 * Exception for failed execution of CLI commands
 */
class ProcessException extends GenericException
{
}
