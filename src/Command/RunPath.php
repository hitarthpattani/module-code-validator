<?php
/**
 * @package     HitarthPattani\CodeValidator
 * @author      Hitarth Pattani <hitarthpattani@gmail.com>
 * @copyright   Copyright © 2021. All rights reserved.
 */
declare(strict_types=1);

namespace HitarthPattani\CodeValidator\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use HitarthPattani\CodeValidator\Filesystem\Module\DirectoryList;
use HitarthPattani\CodeValidator\Shell\OutputFormatter;
use HitarthPattani\CodeValidator\Validator\Pool;

class RunPath extends Command
{
    /**
     * @var string
     */
    const NAME = 'run:path';

    /**
     * @var string
     */
    const OPTION_BRANCH = 'branch';

    /**
     * @var OutputFormatter
     */
    private $outputFormatter;

    /**
     * @var Pool
     */
    private $pool;

    /**
     * @var DirectoryList
     */
    private $directoryList;

    /**
     * @param OutputFormatter $outputFormatter
     * @param Pool $pool
     * @param DirectoryList $directoryList
     */
    public function __construct(
        OutputFormatter $outputFormatter,
        Pool $pool,
        DirectoryList $directoryList
    ) {
        $this->outputFormatter = $outputFormatter;
        $this->pool = $pool;
        $this->directoryList = $directoryList;
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::NAME)
            ->setDescription('Get run path')
            ->addOption(
                self::OPTION_BRANCH,
                null,
                InputOption::VALUE_OPTIONAL,
                'Used for getting path with branch'
            );

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->outputFormatter->execute($output);

        $branch = null;
        if ($input->getOption(self::OPTION_BRANCH)) {
            $branch = $input->getOption(self::OPTION_BRANCH);
        }

        $directoryList = $this->directoryList->get($branch);

        foreach ($directoryList as $validator => $directories) {
            $validator = $this->pool->getValidator($validator);

            $separater = str_repeat(" = ", strlen($validator->name()));
            $padder = str_repeat(" ", strlen($validator->name()));
            $output->writeln(
                sprintf(
                    "\n%s\n\n<output>%s</output>",
                    sprintf(
                        "<title>%s</title>\n<title>%s</title>\n<title>%s</title>",
                        $separater,
                        sprintf("%s%s%s", $padder, $validator->name(), $padder),
                        $separater
                    ),
                    implode("\n", $directories)
                )
            );
        }

        $output->writeln("");
    }
}
