<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{

    /**
     *
     * @Route("/list", name="user_list")
     *
     */
    public function listeUtilisateurAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('UserBundle:User')->findAll();
        return $this->render('user/liste.html.twig', array(
            'users' => $users,
        ));
    }
}
