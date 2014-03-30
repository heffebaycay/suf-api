<?php

namespace Heffe\SteamOpenIdBundle\Entity;

use Doctrine\ORM\EntityManager;
use Fp\OpenIdBundle\Model\UserManager as BaseUserManager;
use Fp\OpenIdBundle\Entity\IdentityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;


class OpenIdUserManager extends BaseUserManager
{
    protected $em;
    protected $im;
    protected $container;

    public function __construct(EntityManager $em, IdentityManager $im, ContainerInterface $container)
    {
        parent::__construct($im);
        $this->em = $em;
        $this->im = $im;
        $this->container = $container;
    }

    public function loadUserByUsername($username)
    {
        $user = parent::loadUserByUsername($username);

        $tmpUser = $this->em->getRepository('HeffeSteamOpenIdBundle:User')->findOneBy(array('steamId' => $user->getUsername()));
        if($tmpUser != null)
        {
            $lastLogin = new LastLogin();
            $lastLogin->setLoginDate(new \DateTime());
            if(!empty($_SERVER['HTTP_USER_AGENT']))
            {
                $lastLogin->setUserAgent($_SERVER['HTTP_USER_AGENT']);
            }

            if(!empty($_SERVER['REMOTE_ADDR']))
            {
                $lastLogin->setIpAddress($_SERVER['REMOTE_ADDR']);
            }

            $tmpUser->addLastLogin($lastLogin);

            $this->em->persist($lastLogin);
            $this->em->persist($tmpUser);
            $this->em->flush();
        }

        return $user;
    }

    public function createUserFromIdentity($identity, array $attributes = array())
    {
        if(!preg_match("/http:\/\/steamcommunity\.com\/openid\/id\/(\d+)/", $identity, $matches))
        {
            throw new \Exception("Invalid SteamId");
        }

        $steamId = $matches[1];

        $user = new User();
        $user->setSteamId($steamId);

        $userWebAPIService = $this->container->get('heffe_sufapi.steamuserwebapi');

        $accessLevel = $userWebAPIService->getUserAccessLevel($user->getSteamId());

        $userGroup = $this->em->getRepository('HeffeSteamOpenIdBundle:Group')->getGroupForAccessLevel($accessLevel);
        if($userGroup != null)
        {
            $user->addGroup($userGroup);
        }

        $userPersona = $userWebAPIService->getUserPersonaName($user->getSteamId());
        $user->setPersona($userPersona);

        $this->em->persist($user);
        $this->em->flush();

        $userIdentity = new OpenIdIdentity();
        $userIdentity->setIdentity($identity);
        $userIdentity->setUser($user);
        $this->em->persist($userIdentity);
        $this->em->flush();

        return $user;
    }

    public function refreshUser(\Symfony\Component\Security\Core\User\UserInterface $user)
    {
        return $user;
    }
}