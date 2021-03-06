<?php
/**
 * @package     HitarthPattani\CodeValidator
 * @author      Hitarth Pattani <hitarthpattani@gmail.com>
 * @copyright   Copyright © 2021. All rights reserved.
 */
declare(strict_types=1);

$magentoRoot = $_ENV['MAGENTO_ROOT'] ?? __DIR__ . '/../../../';

define('CODEVALIDATOR_BP', __DIR__);

if (!defined('BP')) {
    define('BP', realpath($magentoRoot));
}

/**
 * This hack is to prevent Composer crash when 'NonComposerComponentRegistration.php'
 * was moved from app/etc during writable directories mounting.
 */
$files = [
    '/app/etc/registration_globlist.php',
    '/app/etc/NonComposerComponentRegistration.php',
];

foreach ($files as $file) {
    if (!file_exists($magentoRoot . $file) && file_exists($magentoRoot . '/init' . $file)) {
        copy(
            $magentoRoot . '/init' . $file,
            $magentoRoot . $file
        );
    }
}

foreach ([__DIR__ . '/../../autoload.php', __DIR__ . '/vendor/autoload.php'] as $file) {
    if (file_exists($file)) {
        return require $file;
    }
}

throw new RuntimeException('Required file \'autoload.php\' was not found.');
