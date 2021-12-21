# Code Validation for Magento2 Module

A Code Validation module is used to enhance the readability of the magento2 module code and ensure that code meets the Magento Coding Standard. This is a test-runner, written in PHP, used to manage tests that enforce various best practices we expect to see across our entire Magento Best Practice. 

## Usage

This is a command-line utility and can be executed with `bin/codevalidator` for stand-alone Magento2 module. When this project is included via composer as part of a Magento projects, you can execute it with `vendor/bin/codevalidator`.

## Installation

This tool is used to run within a Magento project or within a repo containing a single Magento module. We suggest installing git composer repository. 

### Install via composer through git 

Run following command in magento webroot: 

```bash
composer config repositories.hitarthpattani-git vcs https://github.com/hitarthpattani/module-code-validator.git
composer require hitarthpattani/module-code-validator:1.0.0
bin/magento setup:upgrade
```

It is also possible that you will need to setup `minimum-stability` for now. 

```bash
    "minimum-stability": "alpha",
```

*Important* Composer execute scripts only in root folder, so you need to apply additional `script`. This is required to apply magento-coding-standard for use in PHPCS library.

```bash
    "scripts": {
        "post-install-cmd": [
            "([ $COMPOSER_DEV_MODE -eq 0 ] || vendor/bin/phpcs --config-set installed_paths ../../magento/magento-coding-standard/)"
        ],
        "post-update-cmd": [
            "([ $COMPOSER_DEV_MODE -eq 0 ] || vendor/bin/phpcs --config-set installed_paths ../../magento/magento-coding-standard/)"
        ]
    },
```

Once installed, you can invoke with the following:

```bash
$ vendor/bin/codevalidator
```
