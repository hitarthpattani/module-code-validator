<?php
/**
 * @package     HitarthPattani\CodeValidator
 * @author      Hitarth Pattani <hitarthpattani@gmail.com>
 * @copyright   Copyright © 2021. All rights reserved.
 */
declare(strict_types=1);

namespace HitarthPattani\CodeValidator\Validator;

use HitarthPattani\CodeValidator\Config\Environment\Modules;
use HitarthPattani\CodeValidator\Config\Environment\Validators;
use HitarthPattani\CodeValidator\Filesystem\Module\DirectoryList;
use HitarthPattani\CodeValidator\Filesystem\Driver\File;
use HitarthPattani\CodeValidator\Filesystem\FileSystemException;
use HitarthPattani\CodeValidator\Filesystem\SystemList;
use HitarthPattani\CodeValidator\Filesystem\ConfigFileList;
use HitarthPattani\CodeValidator\Services\IsMagentoInstallation;
use HitarthPattani\CodeValidator\Services\IsStandaloneModule;
use HitarthPattani\CodeValidator\Services\BasePath;
use HitarthPattani\CodeValidator\Shell\OutputFormatter;
use HitarthPattani\CodeValidator\Shell\ShellException;
use HitarthPattani\CodeValidator\Shell\ShellInterface;
use Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Runs the bundled phpcpd against defined module
 */
abstract class AbstractValidator
{
    /**
     * @var string
     */
    const REQUIRE_PASSING = 'require-passing';

    /**
     * @var bool
     */
    protected $isSuccess = true;

    /**
     * @var int
     */
    public $totalCount = 0;

    /**
     * @var int
     */
    public $successCount = 0;

    /**
     * @var DirectoryList
     */
    private $directoryList;

    /**
     * @var BasePath
     */
    protected $basePath;

    /**
     * @var OutputFormatter
     */
    private $outputFormatter;

    /**
     * @var ShellInterface
     */
    private $shell;

    /**
     * @var IsMagentoInstallation
     */
    protected $isMagentoInstallation;

    /**
     * @var IsStandaloneModule
     */
    protected $isStandaloneModule;

    /**
     * @var ConfigFileList
     */
    protected $configFileList;

    /**
     * @var SystemList
     */
    protected $systemList;

    /**
     * @var File
     */
    protected $file;

    /**
     * @var Modules
     */
    protected $modules;

    /**
     * @var Validators
     */
    protected $validators;

    /**
     * @param DirectoryList $directoryList
     * @param BasePath $basePath
     * @param OutputFormatter $outputFormatter
     * @param ShellInterface $shell
     * @param IsMagentoInstallation $isMagentoInstallation
     * @param IsStandaloneModule $isStandaloneModule
     * @param ConfigFileList $configFileList
     * @param SystemList $systemList
     * @param File $file
     * @param Modules $modules
     * @param Validators $validators
     * @SuppressWarnings(PHPMD)
     */
    public function __construct(
        DirectoryList $directoryList,
        BasePath $basePath,
        OutputFormatter $outputFormatter,
        ShellInterface $shell,
        IsMagentoInstallation $isMagentoInstallation,
        IsStandaloneModule $isStandaloneModule,
        ConfigFileList $configFileList,
        SystemList $systemList,
        File $file,
        Modules $modules,
        Validators $validators
    ) {
        $this->directoryList = $directoryList;
        $this->basePath = $basePath;
        $this->outputFormatter = $outputFormatter;
        $this->shell = $shell;
        $this->isMagentoInstallation = $isMagentoInstallation;
        $this->isStandaloneModule = $isStandaloneModule;
        $this->configFileList = $configFileList;
        $this->systemList = $systemList;
        $this->file = $file;
        $this->modules = $modules;
        $this->validators = $validators;
    }

    /**
     * @param array $path
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function command($path): array
    {
        return [];
    }

    /**
     * @param array $paths
     * @return string
     */
    public function process($paths): string
    {
        $message = [];

        $this->isSuccess = true;
        $this->successCount = 0;
        $this->totalCount = count($paths);

        foreach ($paths as $path) {
            try {
                $command = $this->command($path);
                $message[] = sprintf("Running %s\n\n", implode(" ", $command));
                $result = $this->shell->execute($command);
                $message[] = sprintf("<output>%s</output>\n\n", $result->getOutput());
                $this->successCount++;
            } catch (ShellException $exception) {
                $message[] = sprintf("<warning>%s</warning>\n\n", $exception->getMessage());
                $this->isSuccess = false;
            }
        }

        return implode("", $message);
    }

    /**
     * @param string $command
     * @return string
     */
    public function path($command)
    {
        return sprintf("%s/%s", $this->basePath->get(), $command);
    }

    /**
     * @param OutputInterface $output
     * @return bool
     * @throws FileSystemException
     * phpcs:disable Magento2.CodeAnalysis.EmptyBlock.DetectedCatch
     */
    public function execute(OutputInterface $output): bool
    {
        $this->outputFormatter->execute($output);

        $list = $this->directoryList->get();

        foreach ($list as $code => $paths) {
            if ($code == $this->code()) {
                $separater = str_repeat(" = ", strlen($this->name()));
                $padder = str_repeat(" ", strlen($this->name()));
                $output->writeln(
                    sprintf(
                        "\n%s\n\n<output>%s%s</output>",
                        sprintf(
                            "<title>%s</title>\n<title>%s</title>\n<title>%s</title>",
                            $separater,
                            sprintf("%s%s%s", $padder, $this->name(), $padder),
                            $separater
                        ),
                        $this->process($paths),
                        $this->getResult()
                    )
                );
                return $this->getSuccessStatus();
            }
        }

        return true;
    }

    /**
     * @return string
     */
    private function getResult()
    {
        return sprintf(
            "%s/%s completed successfully. %s",
            $this->successCount,
            $this->totalCount,
            $this->getIsSuccess() ?
                '<success>Passed!</success>' :
                (
                $this->getRequirePassing() ?
                    '<failure>Failed!</failure>' :
                    '<warning>Ignoring!</warning>'
                )
        );
    }

    /**
     * @return array
     */
    protected function getConfig()
    {
        return $this->validators->read($this->code());
    }

    /**
     * @return array
     */
    private function getRequirePassing()
    {
        if (isset($this->getConfig()[self::REQUIRE_PASSING])) {
            return (bool)$this->getConfig()[self::REQUIRE_PASSING];
        }

        return true;
    }

    /**
     * @return array
     */
    public function getSuccessStatus()
    {
        if (!$this->getRequirePassing()) {
            return true;
        }

        return $this->isSuccess;
    }

    /**
     * @return array
     */
    public function getIsSuccess()
    {
        return $this->isSuccess;
    }
}
