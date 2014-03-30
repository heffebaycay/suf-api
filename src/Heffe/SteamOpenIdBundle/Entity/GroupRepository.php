<?php

namespace Heffe\SteamOpenIdBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Heffe\SUFAPIBundle\Services\UserAccessLevel;

/**
 * GroupRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GroupRepository extends EntityRepository
{
    public function getGroupForAccessLevel($accessLevel)
    {
        switch($accessLevel)
        {
            case UserAccessLevel::CommunityModerator:
                $role = "ROLE_MODERATOR";
                break;
            case UserAccessLevel::Valve:
                $role = "ROLE_VALVE";
                break;
            default:
                $role = "ROLE_USER";
                break;
        }

        $group = $this->findOneBy(array('role' => $role));

        return $group;
    }
}
