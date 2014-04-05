<?php

namespace Heffe\SUFAPIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Heffe\SUFAPIBundle\Entity\SteamUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SteamUserController extends Controller
{

    /**
     * @Security("has_role('ROLE_MODERATOR')")
     */
    public function getUserProfileBySteamIdAction($steamId64)
    {
        // Trying to fetch the matching user
        $steamUserRepo = $this
                            ->getDoctrine()
                            ->getRepository('HeffeSUFAPIBundle:SteamUser');
        $steamUser = $steamUserRepo->findOneBy( array('steamId' => $steamId64 ) );

        if($steamUser == null)
        {
            // User not found
            // Let's check that this user does exist for Steam
            $personaName = $this->get('heffe_sufapi.steamuserwebapi')->getUserPersonaName($steamId64);
            if($personaName == null)
            {
                // User doesn't exist
                throw new HttpException(Response::HTTP_BAD_REQUEST, "Failed to fetch data for this user");
            }
            else
            {
                // User does exist
                // Let's create it
                $steamUser = $steamUserRepo->createSteamUser( $steamId64, $personaName );
            }
        }

        $cachedPersonaService = $this->get('heffe_sufapi.cachedpersona');
        $cachedPersonaService->refreshPersona($steamUser);

        $userNotes = $this->getDoctrine()->getRepository('HeffeSUFAPIBundle:UserNote')->getUserNotesForUser( $steamUser->getSteamId() );

        // At this point, we're guaranteed to have a valid SteamUser
        return $this->render('HeffeSUFAPIBundle:SteamUser:profile.html.twig', array(
            'steamUser' => $steamUser,
            'userNotes' => $userNotes
        ));
    }

    /**
     * @Security("has_role('ROLE_MODERATOR')")
     */
    public function getUserProfileByVanityURLAction($vanityURL)
    {
        $webAPIService = $this->get('heffe_sufapi.steamuserwebapi');

        $steamId = $webAPIService->resolveVanityURL($vanityURL);

        if($steamId == null)
        {
            throw new HttpException(Response::HTTP_BAD_REQUEST, "Failed to fetch data for this user");
        }

        return $this->redirect(
          $this->generateUrl(
              'heffe_sufapi_getuser_bysteamid',
              array(
                  'steamId64' => $steamId
              )
          )
        );
    }

    /**
     * @Security("has_role('ROLE_MODERATOR')")
     */
    public function lookupUserAction(Request $request)
    {
        $errors = array();

        if($request->getMethod() == 'POST')
        {
            $query = $request->request->get('query');
            if(!empty($query))
            {
                $webAPIService = $this->get('heffe_sufapi.steamuserwebapi');

                $steamId = $webAPIService->identifyUser($query);

                if($steamId == null)
                {
                    $errors[] = 'Unable to find any Steam profile matching your query';
                }
                else
                {
                    return $this->redirect(
                        $this->generateUrl('heffe_sufapi_getuser_bysteamid', array('steamId64' => $steamId))
                    );
                }
            }
            else
            {
                $errors[] = 'Invalid request';
            }
        }

        return $this->render('HeffeSUFAPIBundle:SteamUser:lookup.html.twig', array('errors' => $errors));
    }
}