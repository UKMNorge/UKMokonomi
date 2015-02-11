<?php

namespace UKMNorge\EconomyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use stdClass;

class LayoutController extends Controller
{
	
    public function menuAction( $class_li )
    {
    	$menu = [];

	    $user = $this->get('security.context')->getToken()->getUser();

		if( is_object( $user ) ) {
	    	$item = new stdClass();
	    	$item->path = $this->get('router')->generate('UKMeco_homepage');
	    	$item->title = 'Hjem';
	    	$item->active = true;
	    	$menu[] = $item;

	    	$item = new stdClass();
	    	$item->path = $this->get('router')->generate('UKMeco_budget_homepage');
	    	$item->title = 'Oversikt';
	    	$item->active = true;
	    	$menu[] = $item;
	

			if( $user->hasRole('ROLE_ADMIN') ) {	
		    	$item = new stdClass();
		    	$item->path = $this->get('router')->generate('UKMeco_users_homepage');
		    	$item->title = 'Brukere';
		    	$item->active = false;
		    	$menu[] = $item;
			}
			
/*
	    	$item = new stdClass();
	    	$item->path = $this->get('router')->generate('fos_user_security_logout');
	    	$item->title = 'Logg ut';
	    	$item->active = false;
	    	$menu[] = $item;
*/
	    }
    	
        return $this->render('menuItem.html.twig', array( 'menu' => $menu, 'class_li' => $class_li ));
    }
}
