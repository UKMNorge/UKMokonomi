<?php

namespace MariusMandal\UserBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use FOS\UserBundle\Model\UserManagerInterface;

class DefaultControllerTest extends KernelAwareTest
{
    public function testUserCreate() {

		$user = $this->container
					 ->get('doctrine')
		             ->getRepository('MariusMandalUserBundle:User')
		             ->findOneByEmail( 'testuser@testuser.no' );

		if( !is_null( $user ) ) {
			$em = $this->container->get('doctrine')->getManager();
		    $em->remove($user);
	        $em->flush();
	    }
	    
        $service = $this->container->get('mm.userservice');	    
	    $user_create = $service->create('testuser', 12345678, 'testuser@testuser.no', 'test','test');
	    
	    $this->assertTrue( get_class( $user_create ) == 'MariusMandal\UserBundle\Entity\User' );
    }
    
    public function testUserUpdate() {  
	    $service = $this->container->get('mm.userservice');
	    
	    // Find user by username
	    $user = $service->get('testuser');

	    $user_updated = $service->update( $user->getId(), 'testuser_updated', 87654321, 'testuser_2@testuser.no');
	    $user_updated = $service->update( $user->getId(), $user->getUsername(), $user->getPhone(), 'testuser@testuser.no');
	    
	    $this->assertTrue( $user->getUsername() == 'testuser_updated' );
    }

    public function testUserDeactivate() {
	    $service = $this->container->get('mm.userservice');
	    
	    // Find user by username
	    $user = $service->getByEmail('testuser@testuser.no');
	    
	    $service->deactivate( $user->getId() );
	    
	    $this->assertTrue( !$user->getActive() );
    }

    
    public function testUserActivate() {
	    $service = $this->container->get('mm.userservice');
	    
	    // Find user by username
	    $user = $service->getByEmail('testuser@testuser.no');
	    
	    $service->activate( $user->getId() );
	    
	    $this->assertTrue( $user->getActive() );
    }
}
