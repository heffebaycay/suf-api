<?php

namespace Heffe\SUFAPIBundle\Services;

use Doctrine\ORM\EntityManager;

class SteamUserWebAPI
{
    protected $apiKey;
    protected $em;

    public function __construct($apiKey, EntityManager $entityManager)
    {
        $this->apiKey = $apiKey;
        $this->em = $entityManager;
    }

    /**
     * Fetches the Steam Level for a given Steam User
     *
     * @param string $steamId The SteamId of the user
     * @return int The Steam Level of the user, or -1 on failure
     */
    public function getUserSteamLevel($steamId)
    {
        $methodURL = $this->getWebAPIUrl('IPlayerService', 'GetSteamLevel', 1, array('key' => $this->apiKey, 'steamid' => $steamId));

        $jsonData = @file_get_contents($methodURL);
        if($jsonData !== false)
        {
            $data = json_decode($jsonData);
            if($data != null)
            {
                $level = $data->response->player_level;

                return $level;
            }
            else
            {
                return -1;
            }
        }
        else
        {
            // Request failed
            return -1;
        }
    }

    public function getUserPersonaName($steamId)
    {
        $methodUrl = $this->getWebAPIUrl('ISteamUser', 'GetPlayerSummaries', 2, array('key' => $this->apiKey, 'steamids' => $steamId));

        $jsonData = @file_get_contents($methodUrl);
        $data = json_decode($jsonData);
        if($data != null)
        {
            if( count($data->response->players) == 1 )
            {
                $player = $data->response->players[0];

                return $player->personaname;
            }
        }


        return '<unknown>';
    }

    public function getUserAccessLevel($steamId)
    {
        $methodUrl = $this->getWebAPIUrl('IPlayerService', 'GetBadges', 1, array( 'key' => $this->apiKey, 'steamid' => $steamId ));

        $jsonData = @file_get_contents($methodUrl);

        $data = json_decode($jsonData);

        $accessLevel = UserAccessLevel::User;

        foreach($data->response->badges as $badge)
        {
            if($badge->badgeid == 10)
            {
                // Moderator
                $accessLevel = UserAccessLevel::CommunityModerator;
                break;
            }
            else if($badge->badgeid == 11)
            {
                $accessLevel = UserAccessLevel::Valve;
                break;
            }
        }

        return $accessLevel;
    }

    public function resolveVanityURL($vanityURL)
    {
        //
        $vanityCacheRepo = $this->em->getRepository('HeffeSUFAPIBundle:VanityURLCacheEntry');

        $cacheEntry = $vanityCacheRepo->getCacheEntry($vanityURL);
        if($cacheEntry != null)
        {
            return $cacheEntry->getSteamId();
        }


        $methodURL = $this->getWebAPIUrl('ISteamUser', 'ResolveVanityURL', 1, array('key' => $this->apiKey, 'vanityurl' => $vanityURL));

        $jsonData = @file_get_contents($methodURL);

        if($jsonData !== false)
        {
            $data = json_decode($jsonData);
            if($data != null)
            {
                if($data->response->success == 1)
                {
                    $steamId = $data->response->steamid;

                    $vanityCacheRepo->setCacheEntry($vanityURL, $steamId);

                    return $steamId;
                }
            }
        }

        return null;
    }

    public function identifyUser($query)
    {
        $steamIdRegex = '/(\d{17})/';
        $profileURLRegex = '/^http:\/\/steamcommunity\.com\/profiles\/(\d{17})\/?/';
        $vanityURLRegex = '/^http:\/\/steamcommunity\.com\/id\/([a-zA-Z0-9\-_]+)\/?/';



        if( preg_match($steamIdRegex, $query, $matches) )
        {
            // Input is a SteamID
            $steamId = $matches[1];
        }
        else if( preg_match($profileURLRegex, $query, $matches))
        {
            // Input is /profiles/{SteamId64}
            $steamId = $matches[1];
        }
        else if( preg_match($vanityURLRegex, $query, $matches) )
        {
            $vanityURL = $matches[1];
            $steamId = $this->resolveVanityURL($vanityURL);
        }
        else
        {
            // Assuming input is a vanityURL
            $vanityURL = $query;
            $steamId = $this->resolveVanityURL($vanityURL);
        }

        return $steamId;
    }

    /**
     * @param $interface string The name of the Steam WebAPI interface
     * @param $method string The name of the Steam WebAPI method
     * @param $version integer The version of the Steam WebAPI method
     * @param $params array An array of key => value containing the method parameters
     * @return string
     */
    private function getWebAPIUrl($interface, $method, $version, $params)
    {
        /**
         * %1$s : Interface name
         * %2$s : Method name
         * %3$d : Method version
         */
        $url = sprintf('https://api.steampowered.com/%1$s/%2$s/v%3$d/', $interface, $method, $version);

        $idx = 0;
        foreach($params as $key => $value)
        {
            if($idx == 0)
            {
                $url .= sprintf('?%1$s=%2$s', $key, $value);
            }
            else
            {
                $url .= sprintf('&%1$s=%2$s', $key, $value);
            }
            $idx++;
        }

        return $url;
    }
}

abstract class UserAccessLevel
{
    const User = 0;
    const CommunityModerator = 1;
    const Valve = 2;
}