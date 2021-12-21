<?php
/**
 * @package     HitarthPattani\CodeValidator
 * @author      Hitarth Pattani <hitarthpattani@gmail.com>
 * @copyright   Copyright © 2021. All rights reserved.
 */
declare(strict_types=1);

namespace HitarthPattani\CodeValidator\App;

use Composer\Composer;
use Composer\Factory;
use Composer\IO\BufferIO;
use HitarthPattani\CodeValidator\ExtensionRegistrar;
use HitarthPattani\CodeValidator\Filesystem\SystemList;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Exception;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Container implements ContainerInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\Container
     */
    private $container;

    /**
     * @param string $toolsBasePath
     * @param string $magentoBasePath
     * @throws ContainerException
     */
    public function __construct(string $toolsBasePath, string $magentoBasePath)
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->set('container', $this);
        $containerBuilder->setDefinition('container', new Definition(__CLASS__))
            ->setArguments([$toolsBasePath, $magentoBasePath]);

        $systemList = new SystemList($toolsBasePath, $magentoBasePath);

        $containerBuilder->set(SystemList::class, $systemList);
        $containerBuilder->setDefinition(SystemList::class, new Definition(SystemList::class));

        $containerBuilder->set(Composer::class, $this->createComposerInstance($systemList));
        $containerBuilder->setDefinition(Composer::class, new Definition(Composer::class));

        try {
            $loader = new XmlFileLoader($containerBuilder, new FileLocator($toolsBasePath . '/config/'));
            $loader->load('services.xml');

            foreach (ExtensionRegistrar::getPaths() as $extensionPath) {
                // phpcs:ignore Magento2.Functions.DiscouragedFunction
                if (file_exists($extensionPath . '/config/services.xml')) {
                    $loader = new XmlFileLoader($containerBuilder, new FileLocator($extensionPath . '/config/'));
                    $loader->load('services.xml');
                }
            }
        } catch (Exception $exception) {
            throw new ContainerException($exception->getMessage(), $exception->getCode(), $exception);
        }

        $containerBuilder->compile();

        $this->container = $containerBuilder;
    }

    /**
     * @param SystemList $systemList
     * @return Composer
     */
    private function createComposerInstance(SystemList $systemList): Composer
    {
        $composerFactory = new Factory();
        // phpcs:ignore Magento2.Functions.DiscouragedFunction
        $composerFile = file_exists($systemList->getMagentoRoot() . '/composer.json')
            ? $systemList->getMagentoRoot() . '/composer.json'
            : $systemList->getRoot() . '/composer.json';

        return $composerFactory->createComposer(
            new BufferIO(),
            $composerFile,
            false,
            $systemList->getMagentoRoot()
        );
    }

    /**
     * @param string $containerId
     * @return mixed|object|null
     * @throws ContainerException
     */
    public function get($containerId)
    {
        try {
            return $this->container->get($containerId);
        } catch (Exception $exception) {
            throw new ContainerException(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }

    /**
     * @param string $containerId
     * @return bool
     */
    public function has($containerId): bool
    {
        return $this->container->has($containerId);
    }

    /**
     * @param string $containerId
     * @param object $service
     * @return void
     */
    public function set(string $containerId, $service): void
    {
        $this->container->set($containerId, $service);
    }

    /**
     * @param string $abstract
     * @param array $params
     * @return mixed|object|null
     * @throws ContainerException
     */
    public function create(string $abstract, array $params = [])
    {
        if (empty($params) && $this->has($abstract)) {
            return $this->get($abstract);
        }

        return new $abstract(...array_values($params));
    }
}
