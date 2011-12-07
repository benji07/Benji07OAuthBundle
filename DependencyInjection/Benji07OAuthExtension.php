<?php

namespace Benji07\Bundle\OAuthBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;

/**
 * Benji07OAuth Extension
 */
class Benji07OAuthExtension extends Extension
{
    /**
     * Handles the benji07_o_auth configuration.
     *
     * @param array            $configs   The configurations being loaded
     * @param ContainerBuilder $container The container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }
}