<?php
/**
 * @package     HitarthPattani\CodeValidator
 * @author      Hitarth Pattani <hitarthpattani@gmail.com>
 * @copyright   Copyright © 2021. All rights reserved.
 */
declare(strict_types=1);

namespace HitarthPattani\CodeValidator\App;

/**
 * General interface for DI Container.
 */
interface ContainerInterface extends \Psr\Container\ContainerInterface
{
    /**
     * Resolve the given type from the container.
     *
     * @param string $abstract
     * @param array $params
     * @return mixed
     *
     * @throws ContainerException
     */
    public function create(string $abstract, array $params = []);

    /**
     * Register a binding with the container.
     *
     * @param string $containerId
     * @param object $service
     * @return void
     */
    public function set(string $containerId, $service): void;

    /**
     * @param string $containerId
     * @return mixed
     */
    public function get($containerId);
}
