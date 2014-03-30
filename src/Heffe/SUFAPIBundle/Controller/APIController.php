<?php

namespace Heffe\SUFAPIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
            throw new HttpException(Response::HTTP_FORBIDDEN, "Invalid API Key");
        }
    }

    public function getUserNotesForUserAction(Request $request)
    {
        $key = $request->query->get('key');
        $steamId = $request->query->get('steamid');
        $offset = $request->query->get('offset');
        if(empty($offset))
        {
            $offset = 0;
        }

        // Check key
        $this->checkApiKey($key);

        $userNoteRepo = $this->getDoctrine()->getRepository('HeffeSUFAPIBundle:UserNote');

        $userNotes = $userNoteRepo->getUserNotesForUser($steamId);


        foreach($userNotes as $userNote)
        {
            $userNoteRepo->convertDatesToLocal($userNote, $offset);
        }

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

        $offset = $request->query->get('offset');
        if(empty($offset))
        {
            $offset = 0;
        }

        $serializer = Serializer\SerializerBuilder::create()->build();
        $requestContent = $request->getContent();

        $newUserNote = null;

        if(!empty($requestContent))
        {
            // Deserialize request
            $userNote = $serializer->deserialize($requestContent, 'Heffe\SUFAPIBundle\Entity\UserNote', 'json');

            $userNoteRepo = $this->getDoctrine()->getRepository('HeffeSUFAPIBundle:UserNote');

            $newUserNote = $userNoteRepo->createOrUpdateUserNote($userNote, $key);
            if($newUserNote != null)
            {
                $userNoteRepo->convertDatesToLocal($newUserNote, $offset);
            }
            else
            {
                throw new HttpException(Response::HTTP_BAD_REQUEST, "Failed to create or update UserNote");
            }
        }

        $output = $serializer->serialize($newUserNote, 'json');

        $response = new Response($output);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function deleteUserNoteAction(Request $request)
    {
        $key = $request->query->get('key');
        $this->checkApiKey($key);

        $result = false;

        $userNoteId = $request->request->get('id');
        if(!empty($userNoteId))
        {
            $result = $this->getDoctrine()->getRepository('HeffeSUFAPIBundle:UserNote')->deleteUserNote($userNoteId, $key);
        }

        $output = array('success' => $result);

        $serializer = Serializer\SerializerBuilder::create()->build();
        $output = $serializer->serialize($output, 'json');

        $response = new Response($output);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

}