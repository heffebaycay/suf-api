<?php

namespace Heffe\SUFAPIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $securityContext = $this->container->get('security.context');

        if($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            return $this->render('HeffeSUFAPIBundle:Default:indexAuthenticated.html.twig');
        }
        else
        {
            return $this->render('HeffeSUFAPIBundle:Default:index.html.twig');
        }


    }

    public function loginAction()
    {
        return $this->redirect(
          $this->generateUrl(
              'fp_openid_security_check',
              array(
                  'openid_identifier' => '',
                  '_target_path' => '',
                  '_submit' => 'Login'
              )
          )
        );
    }

    /**
     * @Security("has_role('ROLE_MODERATOR')")
     */
    public function apiKeyAction()
    {
        // Fetch the User object for the logged in user
        $user = $this->get('security.context')->getToken()->getUser();

        // Fetch the active API Contract for the current user
        $contract = $this
                        ->getDoctrine()
                        ->getRepository('HeffeSUFAPIBundle:ApiContract')
                        ->getCurrentContractForUser($user);


        return $this->render('HeffeSUFAPIBundle:Default:apiKey.html.twig', array('contract' => $contract));
    }

    /**
     * @Security("has_role('ROLE_MODERATOR')")
     */
    public function revokeApiKeyAction()
    {
        // Fetch the User object for the logged in user
        $user = $this->getUser();

        $contractRepository = $this
                                ->getDoctrine()
                                ->getRepository('HeffeSUFAPIBundle:ApiContract');

        if($contractRepository != null)
        {
            // Revoke any existing contract
            $contractRepository->revokeCurrentApiContractForUser($user);

            // Generate a new contract
            $contractRepository->createNewApiContractForUser($user);
        }

        return $this->redirect($this->generateUrl('heffe_sufapi_apikey'));
    }
}
