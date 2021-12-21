<?php
/**
 * @package     HitarthPattani\CodeValidator
 * @author      Hitarth Pattani <hitarthpattani@gmail.com>
 * @copyright   Copyright © 2021. All rights reserved.
 */
declare(strict_types=1);

namespace HitarthPattani\CodeValidator\App;

use Throwable;

/**
 * Base exception for general purposes.
 *
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 */
class GenericException extends \Exception
{
    /**
     * @param string $message
     * @param int $code
     * @param Throwable $previous
     * phpcs:disable Generic.CodeAnalysis.UselessOverridingMethod.Found
     */
    public function __construct(string $message, int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
