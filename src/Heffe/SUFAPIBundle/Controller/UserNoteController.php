<?php

namespace Heffe\SUFAPIBundle\Controller;

use Heffe\SUFAPIBundle\Entity\UserNote;
use Heffe\SUFAPIBundle\Forms\Model\UserNoteFormModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserNoteController extends Controller
{
    /**
     * @Security("has_role('ROLE_MODERATOR')")
     */
    public function createUserNoteAction($steamId64, Request $request)
    {
        $formUserNote = new UserNoteFormModel();
        $formUserNote->setSteamId64($steamId64);

        $formBuilder = $this->createFormBuilder($formUserNote);
        $formBuilder
            ->add('content', 'textarea', array('required' =>false, 'error_bubbling' => true))
        ;

        $form = $formBuilder->getForm();

        if($request->getMethod() == 'POST')
        {
            $form->handleRequest($request);

            if($form->isValid())
            {
                $userNote = $formUserNote->toUserNote();
                $user = $user = $this->get('security.context')->getToken()->getUser();

                $newNote = $this->getDoctrine()->getRepository('HeffeSUFAPIBundle:UserNote')->createOrUpdateUserNoteFromUserId($userNote, $user->getId());

                if($newNote == null)
                {
                    // Failed
                    return $this->render('HeffeSUFAPIBundle:UserNote:newUserNote.html.twig', array('form' => $form->createView()));
                }
                else
                {
                    // Redirect to user profile
                    return $this->redirect($this->generateUrl('heffe_sufapi_getuser_bysteamid', array('steamId64' => $steamId64)));
                }
            }
            else
            {
                // Form is not valid
                return $this->render('HeffeSUFAPIBundle:UserNote:newUserNote.html.twig', array('form' => $form->createView()));
            }
        }
        else
        {
            // GET request
            return $this->render('HeffeSUFAPIBundle:UserNote:newUserNote.html.twig', array('form' => $form->createView()));
        }
    }

    /**
     * @Security("has_role('ROLE_MODERATOR')")
     */
    public function updateUserNoteAction($steamId64, $id, Request $request)
    {
        $userNoteRepo = $this
                            ->getDoctrine()
                            ->getRepository('HeffeSUFAPIBundle:UserNote');

        $userNote = $userNoteRepo->getUserNoteByIdAndTargetSteamID($id, $steamId64);

        if($userNote == null)
        {
            throw new HttpException(Response::HTTP_NOT_FOUND, "Requested User Note does not exist");
        }

        $user = $user = $this->get('security.context')->getToken()->getUser();

        if($user->getSteamId() != $userNote->getAuthor()->getSteamId())
        {
            // User must be the original author
            throw new HttpException(Response::HTTP_FORBIDDEN, "You do not have the right permissions to edit this item");
        }

        $target = $userNote->getTarget();

        $formUserNote = new UserNoteFormModel();
        $formUserNote->setSteamId64($steamId64);
        $formUserNote->setContent($userNote->getContent());

        $formBuilder = $this->createFormBuilder($formUserNote);
        $formBuilder
            ->add('content', 'textarea', array('required' =>false, 'error_bubbling' => true))
        ;

        $form = $formBuilder->getForm();

        if($request->getMethod() == 'POST')
        {
            $form->handleRequest($request);

            if($form->isValid())
            {
                $userNote = $formUserNote->toUserNote();
                $userNote->setId($id);

                $newNote = $this->getDoctrine()->getRepository('HeffeSUFAPIBundle:UserNote')->createOrUpdateUserNoteFromUserId($userNote, $user->getId());

                if($newNote != null)
                {
                    return $this->render(
                        'HeffeSUFAPIBundle:UserNote:updateUserNote.html.twig',
                        array(
                            'form' => $form->createView(),
                            'target' => $target,
                            'success' => true
                        )
                    );
                }
            }
        }

        // Error, form not valid or GET request
        return $this->render(
            'HeffeSUFAPIBundle:UserNote:updateUserNote.html.twig',
            array(
                'form' => $form->createView(),
                'target' => $target,
            )
        );
    }
}