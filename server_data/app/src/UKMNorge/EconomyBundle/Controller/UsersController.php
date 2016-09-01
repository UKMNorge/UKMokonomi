<?php

namespace UKMNorge\EconomyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Exception;
use stdClass;

class UsersController extends Controller
{
    public function listAction()
    {
	    $userManager = $this->get('fos_user.user_manager');
	    $users = $userManager->findUsers();
		
		$data = ['users' => ['admins'=>[],'users'=>[]]];
		
		foreach( $users as $user ) {
			if( !$user->isEnabled() ) {
				continue;
			}
			if( $user->hasRole('ROLE_ADMIN') ) {
				$data['users']['admins'][] = $user;
			} else {
				$data['users']['users'][] = $user;
			}
		}
    
        return $this->render( 'UKMecoBundle:Users:list.html.twig', $data );
    }
    
    public function editAction( $username ) {
	    $userManager = $this->get('fos_user.user_manager');
		
		$data = [];
		$data['user'] = $userManager->findUserByUsername( $username );
		
		return $this->render( 'UKMecoBundle:Users:edit.html.twig', $data );
    }
    
    public function deactivateAction( $username ) {
	    $userManager = $this->get('fos_user.user_manager');

		$data = [];
		$data['user'] = $userManager->findUserByUsername( $username );


        return $this->render('UKMecoBundle:Users:deactivate.html.twig', $data );
    }


	public function doDeactivateAction( $username ) {
		$userManager = $this->get('fos_user.user_manager');
		
		$user = $userManager->findUserByUsername( $username );
		
		$user->setEnabled(false);
        $this->get('fos_user.user_manager')->updateUser($user, false);
        $this->getDoctrine()->getManager()->flush();
        
		return $this->redirect( $this->generateUrl('UKMeco_users_homepage'). '#user'.$user->getId() );
	}
    
    public function createAction() {
  		return $this->render( 'UKMecoBundle:Users:edit.html.twig', array('user'=>false) );
    }
    
    public function doCreateAction( Request $request ) {
	    $userManager = $this->get('fos_user.user_manager');
	    
	    $user = $userManager->createUser();
	    $user->setUsername( $request->request->get('username') );    
		$user->setEnabled( true );

	    $user->setPlainPassword( $request->request->get('password') );

		$this->_userData( $user, $request );
   		$this->_userRoles( $user, $request );

   		try {
	        $this->get('fos_user.user_manager')->updateUser($user, false);
	        $this->getDoctrine()->getManager()->flush();
	    } catch( Exception $e ) {
	    	$errordata = [];
	    	$errordata['title'] = 'Kunne ikke opprette bruker!';
	    	$errordata['message'] = $e->getMessage();
	    	$errordata['code'] = $e->getCode();
		    return $this->render( 'UKMecoBundle:Users:error.html.twig', $errordata );
	    }
        
		return $this->redirect( $this->generateUrl('UKMeco_users_homepage'). '#user'.$user->getId() );
    }
    
    public function doUpdateAction( Request $request, $username ) {
   		$userManager = $this->get('fos_user.user_manager');
   		$user = $userManager->findUserByUsername( $username );
   		$currentUser = $this->container->get('security.context')->getToken()->getUser();

		$this->_userData( $user, $request );
		
		if( $user->getId() != $currentUser->getId() ) {
	   		$this->_userRoles( $user, $request );
		}

   		$password = $request->request->get('password');
		if( !empty( $password ) ) {   		
		    $user->setPlainPassword( $request->request->get('password') );
		}

   		try {
	        $this->get('fos_user.user_manager')->updateUser($user, false);
	        $this->getDoctrine()->getManager()->flush();
	    } catch( Exception $e ) {
	    	$errordata = [];
	    	$errordata['title'] = 'Kunne ikke lagre brukerdata!';
	    	$errordata['message'] = $e->getMessage();
	    	$errordata['code'] = $e->getCode();
		    return $this->render( 'UKMecoBundle:Users:error.html.twig', $errordata );
	    }

		return $this->redirect( $this->generateUrl('UKMeco_users_homepage'). '#user'.$user->getId() );
    }
    
    private function _userData( $user, $request ) {
   		$user->setFirstname( $request->request->get('firstname') );
   		$user->setLastname( $request->request->get('lastname') );
   		$user->setEmail( $request->request->get('email') );
   		$user->setPhone( $request->request->get('phone') );
    }
    
    private function _userRoles( $user, $request ) {
	    // USER ROLES
   		$user->addRole( 'ROLE_USER' );
   		switch( $request->request->get('usergroup') ) {
		   	case 'ROLE_ADMIN':
		   		$user->addRole( 'ROLE_ADMIN' );
		   		break;
		   	default:
		   		$user->removeRole( 'ROLE_ADMIN' );
		   		break;
   		}
    }
}
