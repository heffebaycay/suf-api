<?php

namespace Heffe\SUFAPIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use JMS\Serializer;


class APIController extends Controller
{
    private function checkApiKey($key)
    {
        $contract = $this
            ->getDoctrine()
            ->getRepository('HeffeSUFAPIBundle:ApiContract')
            ->getContractForApiKey($key);

        if($contract == null || $contract->getRevoked() == true)
        {
            throw new AccessDeniedException();
        }
    }

    public function getUserNotesForUserAction(Request $request)
    {
        $key = $request->query->get('key');
        $steamId = $request->query->get('steamid');

        // Check key
        $this->checkApiKey($key);

        $userNotes = $this
                        ->getDoctrine()
                        ->getRepository('HeffeSUFAPIBundle:UserNote')
                        ->getUserNotesForUser($steamId);

        $serializer = Serializer\SerializerBuilder::create()->build();
        $output = $serializer->serialize($userNotes, 'json');

        $response = new Response($output);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function updateUserNoteAction(Request $request)
    {
        $key = $request->query->get('key');
        $this->checkApiKey($key);

        $serializer = Serializer\SerializerBuilder::create()->build();
        $requestContent = $request->getContent();

        $newUserNote = null;

        if(!empty($requestContent))
        {
            // Deserialize request
            $userNote = $serializer->deserialize($requestContent, 'Heffe\SUFAPIBundle\Entity\UserNote', 'json');

            $newUserNote = $this->getDoctrine()->getRepository('HeffeSUFAPIBundle:UserNote')->createOrUpdateUserNote($userNote, $key);

        }

        $output = $serializer->serialize($newUserNote, 'json');

        $response = new Response($output);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

}