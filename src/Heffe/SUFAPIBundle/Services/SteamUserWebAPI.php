<?php

namespace Heffe\SUFAPIBundle\Services;

class SteamUserWebAPI
{
    protected $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
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