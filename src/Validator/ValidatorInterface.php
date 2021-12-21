<?php
/**
 * @package     HitarthPattani\CodeValidator
 * @author      Hitarth Pattani <hitarthpattani@gmail.com>
 * @copyright   Copyright © 2021. All rights reserved.
 */
declare(strict_types=1);

namespace HitarthPattani\CodeValidator\Validator;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Validator Interface to define the validation tasks.
 */
interface ValidatorInterface
{
    /**
     * @return string
     */
    public function name(): string;

    /**
     * @return string
     */
    public function code(): string;

    /**
     * @return string
     */
    public function description(): string;

    /**
     * @param OutputInterface $output
     * @return bool
     */
    public function execute(OutputInterface $output): bool;
}
