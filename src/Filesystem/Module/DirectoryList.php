<?php
/**
 * @package     HitarthPattani\CodeValidator
 * @author      Hitarth Pattani <hitarthpattani@gmail.com>
 * @copyright   Copyright © 2021. All rights reserved.
 */
declare(strict_types=1);

namespace HitarthPattani\CodeValidator\Filesystem\Module;

use HitarthPattani\CodeValidator\Config\Environment\Reader;
use HitarthPattani\CodeValidator\Filesystem\Driver\File;
use HitarthPattani\CodeValidator\Filesystem\DirectoryList as FilesystemDirectoryList;
use HitarthPattani\CodeValidator\Filesystem\SystemList;
use HitarthPattani\CodeValidator\Filesystem\FileSystemException;
use HitarthPattani\CodeValidator\Services\IsMagentoInstallation;
use HitarthPattani\CodeValidator\Services\BasePath;
use HitarthPattani\CodeValidator\Shell\ShellException;
use HitarthPattani\CodeValidator\Shell\ShellInterface;
use HitarthPattani\CodeValidator\Shell\Process;
use HitarthPattani\CodeValidator\Shell\ProcessException;

/**
 * Directory path configurations.
 */
class DirectoryList
{
    /**
     * @var string
     */
    const COMMAND = "git diff --name-only --diff-filter=MUXAR %s";

    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var File
     */
    private $file;

    /**
     * @var FilesystemDirectoryList
     */
    private $direcotryList;

    /**
     * @var IsMagentoInstallation
     */
    private $isMagentoInstallation;

    /**
     * @var BasePath
     */
    private $basePath;

    /**
     * @var SystemList
     */
    private $systemList;

    /**
     * @var ShellInterface
     */
    private $shell;

    /**
     * @param Reader $reader
     * @param File $file
     * @param FilesystemDirectoryList $direcotryList
     * @param IsMagentoInstallation $isMagentoInstallation
     * @param BasePath $basePath
     * @param SystemList $systemList
     * @param ShellInterface $shell
     */
    public function __construct(
        Reader $reader,
        File $file,
        FilesystemDirectoryList $direcotryList,
        IsMagentoInstallation $isMagentoInstallation,
        BasePath $basePath,
        SystemList $systemList,
        ShellInterface $shell
    ) {
        $this->reader = $reader;
        $this->file = $file;
        $this->direcotryList = $direcotryList;
        $this->isMagentoInstallation = $isMagentoInstallation;
        $this->basePath = $basePath;
        $this->systemList = $systemList;
        $this->shell = $shell;
    }

    /**
     * Gets a module directory list.
     *
     * @return array
     * @throws FileSystemException
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function get(): array
    {
        $config = $this->reader->read();

        $paths = [];
        if (isset($config[FilesystemDirectoryList::DIR_CODE])) {
            $paths = array_merge_recursive(
                $paths,
                $this->arrange(
                    $config[FilesystemDirectoryList::DIR_CODE],
                    $this->direcotryList->getCode()
                )
            );
        }

        if (isset($config[FilesystemDirectoryList::DIR_DESIGN])) {
            foreach ($config[FilesystemDirectoryList::DIR_DESIGN] as $area => $namespaces) {
                $paths = array_merge_recursive(
                    $paths,
                    $this->arrange(
                        $namespaces,
                        sprintf("%s/%s", $this->direcotryList->getDesign(), $area)
                    )
                );
            }
        }

        $isMagentoInstallation = $this->isMagentoInstallation->check();

        // composer modules
        if (count($paths) == 0
            && isset($config[FilesystemDirectoryList::DIR_SELF])
            && !$isMagentoInstallation
        ) {
            $paths = array_merge_recursive(
                $paths,
                $this->arrange(
                    ['src' => $config[FilesystemDirectoryList::DIR_SELF]],
                    $this->basePath->get()
                )
            );
        }

        // app > code modules
        if (count($paths) == 0
            && isset($config[FilesystemDirectoryList::DIR_SELF])
            && !$isMagentoInstallation
        ) {
            $paths = array_merge_recursive(
                $paths,
                $this->arrange(
                    ['' => $config[FilesystemDirectoryList::DIR_SELF]],
                    $this->basePath->get()
                )
            );
        }

        return $paths;
    }

    /**
     * Arrange namespaces with directory path.
     *
     * @param $namespaces
     * @param $path
     * @return array
     */
    private function arrange($namespaces, $path): array
    {
        $paths = [];

        foreach ($namespaces as $namespace => $validators) {
            foreach ($validators as $validator) {
                $module = sprintf("%s/%s", $path, $namespace);
                if ($this->file->isExists($module)) {
                    $paths[$validator][] = $module;
                }
            }
        }

        return $paths;
    }
}
