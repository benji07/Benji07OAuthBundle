<?php

namespace Benji07\Bundle\OAuthBundle\DependencyInjection\Security\Factory;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\DefinitionDecorator;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\AbstractFactory;

/**
 * OAuth Security Factory
 */
class OAuthFactory extends AbstractFactory
{

    /**
     * Get Position
     *
     * @return string
     */
    public function getPosition()
    {
        return 'pre_auth';
    }

    /**
     * Get Key
     *
     * @return string
     */
    public function getKey()
    {
        return 'oauth';
    }

    /**
     * The listener id
     *
     * @return string
     */
    public function getListenerId()
    {
        return 'benji07.oauth.security.listener';
    }

    /**
     * Get AuthProvider Id
     *
     * @param ContainerBuilder $container      the container
     * @param string           $id             id
     * @param array            $config         config
     * @param string           $userProviderId provider id
     *
     * @return string
     */
    public function createAuthProvider(ContainerBuilder $container, $id, $config, $userProviderId)
    {
        return 'benji07.oauth.security.provider';
    }

}