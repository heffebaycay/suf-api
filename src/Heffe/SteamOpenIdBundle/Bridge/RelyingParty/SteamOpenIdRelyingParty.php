<?php

namespace Heffe\SteamOpenIdBundle\Bridge\RelyingParty;

use Fp\OpenIdBundle\Bridge\RelyingParty\LightOpenIdRelyingParty;
use Symfony\Component\HttpFoundation\Request;

class SteamOpenIdRelyingParty extends LightOpenIdRelyingParty
{
    protected function guessIdentifier(Request $request)
    {
        return 'http://steamcommunity.com/openid';
    }

}