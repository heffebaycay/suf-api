<?php

namespace Heffe\SUFAPIBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Heffe\SteamOpenIdBundle\Entity\User;
use Heffe\SUFAPIBundle\Services\DateService;

/**
 * UserNoteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserNoteRepository extends EntityRepository
{
    public function convertDatesToLocal(UserNote $userNote, $offset)
    {
        $userNote->setDateCreated( DateService::convertDateToLocal($userNote->getDateCreated(), $offset) );
        $userNote->setDateUpdated( DateService::convertDateToLocal($userNote->getDateUpdated(), $offset));
    }

    public function getUserNotesForUser($steamId)
    {
        $qB = $this->createQueryBuilder("un");
        $qB
            ->join('un.target', 't')
            ->where('t.steamId = :steamId')
            ->andWhere('un.removed = false')
            ->setParameter('steamId', $steamId)
            ;

        return $qB->getQuery()->getResult();
    }

    public function createOrUpdateUserNote(UserNote $userNote, $key)
    {
        // Fetching the user matching the key
        $query = $this->_em->createQuery(
            'SELECT u FROM HeffeSteamOpenIdBundle:User u
             JOIN HeffeSUFAPIBundle:ApiContract ac
             WITH 1=1
             JOIN ac.user u2
             WHERE
                u.id = u2.id
                AND
                ac.apiKey = :key
            '
        );
        $query->setParameter('key', $key);

        try
        {
            $user = $query->getSingleResult();
        }
        catch(NoResultException $nre)
        {
            $user = null;
        }

        if($user == null)
        {
            return null;
        }

        // Empty note is not valid
        $content = trim($userNote->getContent());
        if(empty($content))
        {
            return null;
        }

        $userNote->setContent( htmlspecialchars($userNote->getContent()) );


        if($userNote->getId() > 0)
        {
            $newNote = $this->findOneBy(array('id' => $userNote->getId()));
            if($newNote == null)
            {
                return null;
            }

            $id1 = $newNote->getAuthor()->getId();
            $id2 = $user->getId();

            if($newNote->getAuthor()->getId() != $user->getId())
            {
                // Actor isn't the original author
                // Updating denied
                return null;
            }

            $newNote->setContent($userNote->getContent());
            $newNote->setDateUpdated( new \DateTime() );
        }
        else
        {
            // Fetching the target
            $queryTarget = $this->_em->createQuery(
                'SELECT su FROM HeffeSUFAPIBundle:SteamUser su
                 WHERE su.steamId = :steamId
                '
            );
            $queryTarget->setParameter('steamId', $userNote->getTarget()->getSteamId());

            try
            {
                $target = $queryTarget->getSingleResult();
            }
            catch(NoResultException $nre)
            {
                $target = null;
            }

            if($target == null)
            {
                $target = new SteamUser();
                $target->setSteamId( $userNote->getTarget()->getSteamId() );
            }

            $newNote = new UserNote();
            $newNote->setContent( $userNote->getContent() );
            $newNote->setAuthor( $user );
            $newNote->setTarget( $target );
            $newNote->setDateCreated(new \DateTime());

            $this->_em->persist($user);
            $this->_em->persist($target);
        }

        $this->_em->persist($newNote);
        $this->_em->flush();

        return $newNote;
    }


    public function deleteUserNote($userNoteId, $key)
    {
        // Fetching the user matching the key
        $query = $this->_em->createQuery(
            'SELECT u FROM HeffeSteamOpenIdBundle:User u
             JOIN HeffeSUFAPIBundle:ApiContract ac
             WITH 1=1
             JOIN ac.user u2
             WHERE
                u.id = u2.id
                AND
                ac.apiKey = :key
            '
        );
        $query->setParameter('key', $key);

        try
        {
            $user = $query->getSingleResult();
        }
        catch(NoResultException $nre)
        {
            $user = null;
        }

        if($user == null)
        {
            return null;
        }


        if($userNoteId > 0)
        {
            // Fetch user note to remove from the Database
            $dbUserNote = $this->findOneBy(array('id' => $userNoteId));
            if($dbUserNote != null)
            {
                // Only the author of the note is allowed to remove it
                if($dbUserNote->getAuthor()->getId() == $user->getId())
                {
                    $dbUserNote->setRemoved(true);
                    $dbUserNote->setDateRemoved(new \DateTime());

                    $this->_em->persist($dbUserNote);
                    $this->_em->flush();

                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
}
