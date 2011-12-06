<?php

namespace Benji07\Bundle\OAuthBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class AddProvidersPass implements CompilerPassInterface
{
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