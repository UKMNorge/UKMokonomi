<?php

namespace UKMNorge\EconomyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
   	    $user = $this->get('security.context')->getToken()->getUser();
   		if( is_object( $user ) ) {
	        return $this->render('UKMecoBundle:Default:index.html.twig', array());
	    }
	    return $this->redirect( $this->get('router')->generate('fos_user_security_login') );
    }
}
