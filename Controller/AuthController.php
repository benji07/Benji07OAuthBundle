<?php

namespace Benji07\Bundle\OAuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Buzz\Browser;

use Benji07\Bundle\OAuthBundle\OAuthManager;
use Benji07\Bundle\OAuthBundle\GithubProvider;
use Benji07\Bundle\OAuthBundle\FacebookProvider;

/**
 * Benji07 OAuth AuthController
 */
class AuthController extends Controller
{

    /**
     * Secure action
     *
     * @Route("/oauth/secure", name="oauth_secure")
     *
     */
    public function secureAction()
    {
    }

    /**
     * Login action
     *
     * @param string $name provider name
     *
     * @Route("/oauth/{name}", name="oauth_login")
     *
     * @return Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function loginAction($name)
    {
        $referer = $this->getRequest()->server->get('HTTP_REFERER');

        $redirect = $this->generateUrl('oauth_secure', array(
            'name' => $name,
            'referer' => $referer
        ), true);

        $provider = $this->get('benji07.oauth.manager')->getProvider($name);

        return $this->redirect($provider->getAuthorizeUri($this->getRequest(), $redirect));
    }

    /**
     * Link action
     *
     * @param string $name provider name
     *
     * @Route("/oauth/link/{name}", name="oauth_link")
     *
     * @return Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function linkAction($name)
    {
        $referer = $this->getRequest()->server->get('HTTP_REFERER', '/');

        $provider = $this->get('benji07.oauth.manager')->getProvider($name);

        if (false === $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($referer);
        }

        $redirect = $this->generateUrl('oauth_link_callback', array(
            'name' => $name,
            'referer' => $referer), true);

        return $this->redirect($provider->getAuthorizeUri($this->getRequest(), $redirect));
    }

    /**
     * Link callback action
     *
     * @param string $name provider name
     *
     * @Route("/oauth/link/{name}/callback", name="oauth_link_callback")
     *
     * @return Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function callbackAction($name)
    {
        $referer = $this->getRequest()->query->get('referer');

        $redirect = $this->generateUrl('oauth_link_callback', array('name' => $name, 'referer' => $referer), true);

        $provider = $this->get('benji07.oauth.manager')->getProvider($name);

        if (false === $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($referer);
        }

        try {
            $token = $provider->getAccessToken($this->getRequest(), $redirect);

            $this->get('benji07.oauth.manager')->getUserManager()->link($provider, $user, $token);

            $this->getRequest()->getSession()->setFlash('success', strtr('Your %provider% is now linked', array('%provider' => $name)));

        } catch (\Exception $e) {
            $this->getRequest()->getSession()->setFlash('error', strtr($e->getMessage(), array('%provider%')));
        }

        return $this->redirect($referer);
    }

    /**
     * Unlink action
     *
     * @param string $name provider name
     *
     * @Route("/oauth/unlink/{name}", name="oauth_unlink")
     *
     * @return Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function unlinkAction($name)
    {
        $referer = $this->getRequest()->query->get('referer');

        if (false === $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($referer);
        }

        $provider = $this->get('benji07.oauth.manager')->getProvider($name);

        $user = $this->getUser();

        try {
            $this->get('benji07.oauth.manager')->getUserManager()->unlink($provider, $user);

            $this->getRequest()->getSession()->setFlash('success', 'We unlinked successfully your %provider% in account', array('%provider%' => $name));

        } catch (\Exception $e) {
            $this->getRequest()->getSession()->setFlash('error', strtr($e->getMessage(), array('%provider%')));
        }

        return $this->redirect($referer);
    }

    /**
     * Get user
     *
     * @return Symfony\Component\Security\Core\User\UserInterface|null
     */
    public function getUser()
    {
        $token = $this->get('security.context')->getToken();

        if ($token !== null) {
            return $token->getUser();
        }

        return null;
    }
}