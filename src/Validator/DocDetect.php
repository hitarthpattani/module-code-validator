<?php
/**
 * @package     HitarthPattani\CodeValidator
 * @author      Hitarth Pattani <hitarthpattani@gmail.com>
 * @copyright   Copyright © 2021. All rights reserved.
 */
declare(strict_types=1);

namespace HitarthPattani\CodeValidator\Validator;

/**
 * Runs the phpcpd against defined module
 */
class DocDetect extends AbstractValidator implements ValidatorInterface
{
    /**
     * Keys of directory configuration.
     */
    const CODE = 'doc_detect';

    /**
     * @var string
     */
    const KEY_DOCS = 'docs';

    /**
     * @param array $paths
     * @return string
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function process($paths): string
    {
        $message = [];

        $this->isSuccess = true;
        $this->successCount = 0;
        $this->totalCount = 0;

        $docs = $this->getConfig()[self::KEY_DOCS];

        foreach ($docs as $fileName) {
            if (!$this->isMagentoInstallation->check()) {
                if ($this->isStandaloneModule->check()) {
                    $filePath = sprintf("%s/%s", $this->systemList->getMagentoRoot(), $fileName);
                } else {
                    $filePath = sprintf("%s/%s", $this->systemList->getRoot(), $fileName);
                }

                // In case correct extensions dir found and exists - assertion is run
                $localResult = (bool)$this->file->isFile($filePath);

                if ($localResult) {
                    $message[] = sprintf("<output>%s exists...</output>\n", $filePath);
                    $this->successCount++;
                } else {
                    $message[] = sprintf("<warning>%s DO NOT exists...</warning>\n", $filePath);
                    $this->isSuccess = false;
                }

                $this->totalCount++;
            } else {
                foreach ($paths as $path) {
                    $message[] = sprintf("Running in %s for %s\n\n", $path, $fileName);

                    // We validate all dirs from our paths
                    if (!$this->file->isDirectory($path)) {
                        continue;
                    }

                    // In each dir we go deeper and select extension dir to validate
                    foreach ($this->file->getDirectoryIterator($path) as $fileInfo) {
                        if ($this->canSkip($fileInfo)) {
                            continue;
                        }

                        $filePath = sprintf("%s/%s", $fileInfo->getPathname(), $fileName);

                        // In case correct extensions dir found and exists - assertion is run
                        $localResult = (bool)$this->file->isFile($filePath);

                        if ($localResult) {
                            $message[] = sprintf("<output>%s exists...</output>\n", $filePath);
                            $this->successCount++;
                        } else {
                            $message[] = sprintf("<warning>%s DO NOT exists...</warning>\n", $filePath);
                            $this->isSuccess = false;
                        }

                        $this->totalCount++;
                    }
                    $message[] = sprintf("\n");
                }
            }
        }

        $message[] = sprintf("\n");

        return implode("", $message);
    }

    /**
     * @param \DirectoryIterator $fileInfo
     * @return bool
     */
    private function canSkip($fileInfo)
    {
        // Files and dots are skipped
        if ($fileInfo->isDot() || $fileInfo->isDir() === false) {
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return 'Documentation Detector';
    }

    /**
     * @return string
     */
    public function code(): string
    {
        return self::CODE;
    }

    /**
     * @return string
     */
    public function description(): string
    {
        return 'Run checker to validate module specific documentations against environment configured paths/files';
    }
}
