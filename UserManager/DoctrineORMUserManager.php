<?php

namespace Benji07\Bundle\OAuthBundle\UserManager;

use Doctrine\ORM\EntityManager;

use Doctrine\Common\Util\Inflector;

/**
 * UserManager for DoctrineORM
 */
class DotrineORMUserManager implements UserManagerInterface
{
    private $em;

    private $class;

    /**
     * __construct
     *
     * @param EntityManager $em    the entity manager
     * @param string        $class the user class
     */
    public function __construct(EntityManager $em, $class)
    {
        $this->em = $em;

        $this->class = $class;
    }

    /**
     * Find a user
     *
     * @param OAuthProvider $provider the provider
     * @param string|array  $token    the token
     *
     * @return UserInterface|null
     */
    public function findUser(OAuthProvider $provider, $token)
    {
        $repository = $this->em->getRepository($this->class);

        $id = $token;

        if (is_array($token)) {
            $id = $token['oauth_token'];
        }

        return $repository->findOneBy(array($provider->getIdColumn() => $id));
    }

    /**
     * Link a user to a provider
     *
     * @param OAuthProvider $provider the provider
     * @param UserInterface $user     the user
     * @param string|array  $token    the token
     *
     * @throw \Exception if we can't link the user
     */
    public function link(OAuthProvider $provider, UserInterface $user, $token)
    {
        if (!is_array($token)) {
            $token = array($token);
        }

        $data = array_combine($provider->getColumns(), $token);

        foreach ($data as $column => $value) {
            $method = 'set' . Inflector::classify($column);

            $user->{$method}($value);
        }

        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * Unlink a user with  a provider
     *
     * @param OAuthProvider $provider the provider
     * @param UserInterface $user     the user
     *
     * @throw \Exception if we can't unlink the user
     */
    public function unlink(OAuthProvider $provider, UserInterface $user)
    {
        foreach ($provider->getColumns() as $column) {
            $method = 'set' . Inflector::classify($column);

            $user->{$method}(null);
        }

        $this->em->persist($user);
        $this->em->flush();
    }
}