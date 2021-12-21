<?php
/**
 * @package     HitarthPattani\CodeValidator
 * @author      Hitarth Pattani <hitarthpattani@gmail.com>
 * @copyright   Copyright © 2021. All rights reserved.
 */
declare(strict_types=1);

namespace HitarthPattani\CodeValidator\Validator;

use HitarthPattani\CodeValidator\Filesystem\FileSystemException;

/**
 * Runs header comment validator
 */
class HeaderComments extends AbstractValidator implements ValidatorInterface
{
    /**
     * Task code.
     */
    const CODE = 'header_comments';

    /**
     * @var string
     */
    const KEY_REGEX = 'regex';

    /**
     * @param array $paths
     * @return string
     * @throws FileSystemException
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * phpcs:disable Generic.Metrics.NestingLevel.TooHigh
     */
    public function process($paths): string
    {
        $message = [];

        $this->isSuccess = true;
        $this->successCount = 0;
        $this->totalCount = 0;

        $regex = $this->getConfig()[self::KEY_REGEX];

        $allowedExtensions = array_keys($regex);
        $modules = $this->modules->read();

        foreach ($allowedExtensions as $extension) {
            foreach ($paths as $path) {
                try {
                    $files = $this->file->getRecursiveFileIterator($path, sprintf("/^.+\.%s$/i", $extension));
                } catch (\Exception $exception) {
                    $files = [$path];
                }
                foreach ($files as $file) {
                    if (!is_object($file)) {
                        $file = new \SplFileInfo($file);
                    }

                    // get file path for content
                    $path = $file->getRealPath();

                    if (false !== strpos($path, 'vendor')) {
                        continue;
                    }

                    // get file content for
                    $contents = $this->file->fileGetContents($path);

                    $namespace = $this->getNamespace($path);

                    $extension = $file->getExtension();
                    if ($extension && isset($regex[$extension])) {
                        if (!preg_match(sprintf("/%s/Uis", $regex[$extension]), $contents, $matches)) {
                            $message[] = sprintf("<warning>%s failed validation!</warning> \n", $path);
                            $this->isSuccess = false;
                        } else {
                            if ($namespace === false ||
                                preg_match(
                                    sprintf(
                                        "/\@package( *)%s(.?)%s/i",
                                        implode("|", $modules),
                                        preg_quote($namespace, '/')
                                    ),
                                    $matches[0]
                                )
                            ) {
                                $message[] = sprintf("<output>%s passed validation!</output> \n", $path);
                                $this->successCount++;
                            } else {
                                $this->isSuccess = false;
                                $message[] = sprintf(
                                    "<warning>%s is applied with invalid namespace!</warning> \n",
                                    $path
                                );
                            }
                        }
                    } else {
                        if (!in_array($extension, $allowedExtensions)) {
                            $message[] = sprintf("<output>%s passed validation!</output> \n", $path);
                            $this->successCount++;
                        } else {
                            $message[] = sprintf("<warning>%s unsupported extension!</warning> \n", $path);
                            $this->isSuccess = false;
                        }
                    }

                    $this->totalCount++;
                }
            }
        }

        $message[] = sprintf("\n");

        return implode("", $message);
    }

    /**
     * @param string $file
     * @return bool|string
     */
    private function getNamespace($file)
    {
        $fileData = explode('/', $file);
        $replace = false;
        $key = array_search('code', $fileData);
        if ($key === false) {
            $key = array_search('vendor', $fileData);
            $replace = true;
        }
        if ($key !== false && isset($fileData[$key + 2])) {
            $namespace = $fileData[$key + 2];
            if ($replace) {
                $namespace = str_replace('module-', '', $namespace);
            }
            return $namespace;
        }
        return false;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return 'Header Comments';
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
        return 'Run checker to ensures header comments reflect our coding standard for different file extensions';
    }
}
