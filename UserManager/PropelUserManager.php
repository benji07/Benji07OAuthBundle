<?php

namespace Benji07\Bundle\OAuthBundle\UserManager;

use Benji07\Bundle\OAuthBundle\Provider\OAuthProvider;

use Symfony\Component\Security\Core\User\UserInterface;

use \PropelQuery;

use Doctrine\Common\Util\Inflector;

/**
 * UserManager for Propel
 */
class PropelUserManager implements UserManagerInterface
{
    protected $class;

    /**
     * __construct
     *
     * @param array $options the options
     */
    public function __construct(array $options)
    {
        $this->class = $options['class'];
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
        $id = $token;

        if (is_array($token)) {
            $id = $token['oauth_token'];
        }

        $method = 'filterBy' . Inflector::classify($provider->getIdColumn());

        $user = PropelQuery::from($this->class)->$method($token)->findOne()

        return $user;
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

        $user->save();
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

        $user->save();
    }

    /**
     * Check if the user is likn with the provider
     *
     * @param OAuthProvider $provider the provider
     * @param UserInterface $user     the user
     *
     * @return boolean
     */
    public function isLink(OAuthProvider $provider, UserInterface $user)
    {
        $method = 'get' . Inflector::classify($provider->getIdColumn());

        return null !== $user->{$method}();
    }
}