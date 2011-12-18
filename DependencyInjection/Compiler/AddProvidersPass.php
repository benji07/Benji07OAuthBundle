<?php

namespace Benji07\Bundle\OAuthBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Add Provider pass
 *
 * @author Benjamin Lévêque <benjamin@leveque.me>
 */
class AddProvidersPass implements CompilerPassInterface
{
    /**
     * Process container
     *
     * @param ContainerBuilder $container the container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('benji07.oauth.manager')) {
            return;
        }

        foreach ($container->findTaggedServiceIds('benji07.oauth.provider') as $id => $tags) {
            $definition = $container->getDefinition('benji07.oauth.manager');
            $definition->addMethodCall('addProvider', array(new Reference($id)));
        }
    }
}