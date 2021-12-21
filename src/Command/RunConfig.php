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
use Symfony\Component\Console\Output\OutputInterface;
use HitarthPattani\CodeValidator\Config\Environment\Validators;
use HitarthPattani\CodeValidator\Filesystem\FileSystemException;
use HitarthPattani\CodeValidator\Shell\OutputFormatter;
use HitarthPattani\CodeValidator\Validator\Pool;

class RunConfig extends Command
{
    /**
     * @var string
     */
    const NAME = 'run:config';

    /**
     * @var OutputFormatter
     */
    private $outputFormatter;

    /**
     * @var Pool
     */
    private $pool;

    /**
     * @var Validators
     */
    private $validatorsConfig;

    /**
     * @param OutputFormatter $outputFormatter
     * @param Pool $pool
     * @param Validators $validatorsConfig
     */
    public function __construct(
        OutputFormatter $outputFormatter,
        Pool $pool,
        Validators $validatorsConfig
    ) {
        $this->outputFormatter = $outputFormatter;
        $this->pool = $pool;
        $this->validatorsConfig = $validatorsConfig;
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::NAME)
            ->setDescription('Run to get config');

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     * @throws FileSystemException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->outputFormatter->execute($output);

        $validators = $this->pool->getValidators();

        foreach ($validators as $validator) {
            $separater = str_repeat(" = ", strlen($validator->name()));
            $padder = str_repeat(" ", strlen($validator->name()));

            $configs = $this->validatorsConfig->read($validator->code());

            $output->writeln(
                sprintf(
                    "\n%s\n<output>%s</output>",
                    sprintf(
                        "<title>%s</title>\n<title>%s</title>\n<title>%s</title>",
                        $separater,
                        sprintf("%s%s%s", $padder, $validator->name(), $padder),
                        $separater
                    ),
                    $this->formatConfig($configs)
                )
            );
        }

        $output->writeln("");
    }

    /**
     * @param $configs
     * @param int $count
     * @return string
     */
    private function formatConfig($configs, $count = 0)
    {
        $configString = '';

        foreach ($configs as $key => $value) {
            $configString .= sprintf(
                "\n%s<warning>%s</warning> %s",
                str_repeat(str_repeat(" ", 4), $count),
                is_numeric($key)? "-": $key . ":",
                is_array($value)? $this->formatConfig($value, ($count + 1)): $value
            );
        }

        return $configString;
    }
}
