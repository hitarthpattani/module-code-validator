<?php
/**
 * @package     HitarthPattani\CodeValidator
 * @author      Hitarth Pattani <hitarthpattani@gmail.com>
 * @copyright   Copyright © 2021. All rights reserved.
 */
declare(strict_types=1);

error_reporting(E_ALL);
date_default_timezone_set('UTC');

require_once __DIR__ . '/autoload.php';

use HitarthPattani\CodeValidator\App\Container;

return new Container(CODEVALIDATOR_BP, BP);
