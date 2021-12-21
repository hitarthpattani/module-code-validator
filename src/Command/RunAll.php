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

class RunAll extends Command
{
    /**
     * @var string
     */
    const NAME = 'run:all';

    /**
     * @var int
     */
    const RESULT_SUCCESS = 0;
    const RESULT_FAIL = 1;

    /**
     * @var Pool
     */
    protected $pool;

    /**
     * @var Summery
     */
    protected $summery;

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
        $this->setName(self::NAME)
            ->setDescription('Run All Commands');

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
        $validators = $this->pool->getValidators();

        $result = self::RESULT_SUCCESS;

        foreach ($validators as $validator) {
            if (!$validator->execute($output) && $result == self::RESULT_SUCCESS) {
                $result = self::RESULT_FAIL;
            }
        }

        $output->writeln($this->summery->execute($validators));

        return $result;
    }
}
