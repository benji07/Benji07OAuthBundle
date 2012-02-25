<?php

namespace Benji07\Bundle\OAuthBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;

use Benji07\Bundle\OAuthBundle\DependencyInjection\Compiler\AddProvidersPass;
use Benji07\Bundle\OAuthBundle\DependencyInjection\Security\Factory\OAuthFactory;

/**
 * Benji07 OAuth Bundle
 *
 * @author Benjamin Lévêque <benjamin@leveque.me>
 */
class Benji07OAuthBundle extends Bundle
{
    /**
     * build container
     *
     * @param ContainerBuilder $container the container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new AddProvidersPass());

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new OAuthFactory());
    }
}