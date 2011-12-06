<?php

namespace Benji07\Bundle\OAuthBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;

use Benji07\Bundle\OAuthBundle\DependencyInjection\Compiler\AddProvidersPass;

/**
 * Benji07 OAuth Bundle
 */
class Benji07OAuthBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new AddProvidersPass());
    }
}