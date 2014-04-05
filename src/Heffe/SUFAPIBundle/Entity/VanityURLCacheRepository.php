<?php

namespace Heffe\SUFAPIBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

/**
 * VanityURLCacheRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class VanityURLCacheRepository extends EntityRepository
{
    public function getCacheEntry($vanityURL)
    {
        $query = $this->_em->createQuery(
            'SELECT v FROM HeffeSUFAPIBundle:VanityURLCacheEntry v
             WHERE
                v.vanityURL = :vanityURL
             AND
                v.dateCreated > :dateLimit
             '
        );

        $dateLimit = new \DateTime();
        // Cache validity limit is 5 minutes
        // So we're looking for the entries created in the last 5 minutes
        $dateLimit->sub( new \DateInterval('PT5M') );

        $query->setParameter('vanityURL', $vanityURL);
        $query->setParameter('dateLimit', $dateLimit);

        try
        {
            $cacheEntry = $query->getSingleResult();
        }
        catch(NoResultException $nre)
        {
            $cacheEntry = null;
        }

        return $cacheEntry;
    }

    public function setCacheEntry($vanityURL, $steamId)
    {
        $entry = $this->findOneBy(array('vanityURL' => $vanityURL));

        if($entry != null)
        {
            // Entry already exists, we'll just update it
            $entry->setSteamId($steamId);
            $entry->setDateCreated(new \DateTime());
        }
        else
        {
            $entry = new VanityURLCacheEntry();
            $entry->setSteamId($steamId);
            $entry->setVanityURL($vanityURL);
            $entry->setDateCreated(new \DateTime());
        }

        $this->_em->persist($entry);
        $this->_em->flush();
    }
}
