<?php
/**
 * @package     HitarthPattani\CodeValidator
 * @author      Hitarth Pattani <hitarthpattani@gmail.com>
 * @copyright   Copyright © 2021. All rights reserved.
 */
declare(strict_types=1);

namespace HitarthPattani\CodeValidator\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use HitarthPattani\CodeValidator\Validator\Pool;
use HitarthPattani\CodeValidator\Validator\Summery;
use HitarthPattani\CodeValidator\Validator\ValidatorException;
use HitarthPattani\CodeValidator\Validator\ValidatorInterface;

abstract class AbstractRun extends Command
{
    /**
     * @var int
     */
    const RESULT_SUCCESS = 1;
    const RESULT_FAIL = 0;

    /**
     * @var Pool
     */
    protected $pool;

    /**
     * @var Summery
     */
    private $summery;

    /**
     * @param Pool $pool
     * @param Summery $summery
     */
    public function __construct(
        Pool $pool,
        Summery $summery
    ) {
        $this->pool = $pool;
        $this->summery = $summery;
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName($this->getCommandName())
            ->setDescription($this->getValidator()->description());

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $result = self::RESULT_SUCCESS;

        try {
            $validator = $this->getValidator();

            if (!$validator->execute($output) && $result == self::RESULT_SUCCESS) {
                $result = self::RESULT_FAIL;
            }

            $output->writeln($this->summery->execute([$validator]));
        } catch (ValidatorException $e) {
            $output->writeln(sprintf("<error>%s</error>", $e->getMessage()));
            $result = self::RESULT_FAIL;
        }

        return $result;
    }

    /**
     * @return ValidatorInterface
     */
    abstract public function getValidator();

    /**
     * @return string
     */
    abstract public function getCommandName();
}
