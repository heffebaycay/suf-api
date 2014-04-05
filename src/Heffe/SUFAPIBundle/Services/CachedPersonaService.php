<?php

namespace Heffe\SUFAPIBundle\Services;

use Doctrine\ORM\EntityManager;
use Heffe\SUFAPIBundle\Entity\CachedPersona;
use Heffe\SUFAPIBundle\Entity\SteamUser;

class CachedPersonaService
{
    protected $webAPIService;
    protected $em;
    protected $personaCacheDuration;

    public function __construct(SteamUserWebAPI $webAPIService, EntityManager $entityManager, $personaCacheDuration)
    {
        $this->webAPIService = $webAPIService;
        $this->em = $entityManager;
        $this->personaCacheDuration = $personaCacheDuration;
    }

    public function refreshPersona(SteamUser $steamUser)
    {
        if($steamUser == null)
        {
            return false;
        }

        $dateLimit = new \DateTime();
        $dtInterval = new \DateInterval( sprintf('PT%dM', $this->personaCacheDuration) );
        $dateLimit->sub( $dtInterval );


        $persona = $steamUser->getPersona();

        if($persona == null)
        {
            $strPersona = $this->webAPIService->getUserPersonaName($steamUser->getSteamId());
            if($strPersona != null)
            {
                $persona = new CachedPersona();
                $persona->setDateCreated(new \DateTime());
                $persona->setPersonaName($strPersona);

                $steamUser->setPersona($persona);

                $this->em->persist($persona);
                $this->em->persist($steamUser);
                $this->em->flush();

                return true;
            }
        }
        else
        {
            if(

                (
                    $persona->getDateUpdated() == null && $persona->getDateCreated() < $dateLimit
                ) ||
                (
                    $persona->getDateUpdated() != null && $persona->getDateUpdated() < $dateLimit
                )
            )
            {
                // Refresh needed
                $strPersona = $this->webAPIService->getUserPersonaName($steamUser->getSteamId());
                if($strPersona != null)
                {
                    $persona->setPersonaName($strPersona);
                    $persona->setDateUpdated(new \DateTime());

                    $this->em->persist($persona);
                    $this->em->flush();

                    return true;
                }
            }
        }

        return false;
    }

}