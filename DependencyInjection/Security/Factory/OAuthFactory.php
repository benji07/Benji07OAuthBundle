<?php

namespace Benji07\Bundle\OAuthBundle\DependencyInjection\Security\Factory;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\DefinitionDecorator;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\AbstractFactory;

class OAuthFactory extends AbstractFactory
{

    public function getPosition()
    {
        return 'pre_auth';
    }

    public function getKey()
    {
        return 'oauth';
    }

    public function getListenerId()
    {
        return 'benji07.oauth.security.listener';
    }

    public function createAuthProvider(ContainerBuilder $container, $id, $config, $userProviderId)
    {
        return 'benji07.oauth.security.provider';
    }

}