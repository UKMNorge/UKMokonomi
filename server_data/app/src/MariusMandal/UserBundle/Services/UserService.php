<?php

namespace MariusMandal\UserBundle\Services;

use FOS\UserBundle\Security\UserProvider as FOSProvider;
use Symfony\Component\DependencyInjection\ContainerInterface;
use FOS\UserBundle\Model\UserProviderInterface;
use \MariusMandal\UserBundle\User;
use Exception;

class UserService extends FOSProvider
{
    /**
     *
     * @var ContainerInterface 
     */
    protected $container;


	/**
	 * 
	 * Class constructor
	 * @param UserManagerInterface
	 * @param ContainerInterface
	 *
	*/
    public function __construct(UserProviderInterface $userProvider, ContainerInterface $container) {
        parent::__construct($userProvider);
        $this->container = $container;
    }
    
    /**
     *
     * Get an user by username
     * @param String username
	 *
     * return user
     */
    public function get( $username ) {
		$em = $this->container->get('doctrine')->getRepository('MariusMandalUserBundle:User');
		
		$user = $em->findOneByUsername( $username );
		if( is_null( $user ) ) {
			throw new Exception('User not found!', 1051);
		}
	    
	    return $user;
    }

    /**
     *
     * Get an user by email
     * @param String email
	 *
     * return user
     */
    public function getByEmail( $email ) {
		$em = $this->container->get('doctrine')->getRepository('MariusMandalUserBundle:User');
		
		$user = $em->findOneByEmail( $email );
		if( is_null( $user ) ) {
			throw new Exception('User not found!', 1051);
		}
	    
	    return $user;
    }
       
    /**
     *
     * Create an user
     * @param String username 
     * @param String phone
     * @param String e-mail
     * @param String password
     *
     * return User object
     */
    public function create( $username, $phone, $email, $password ) {
		$em = $this->container->get('doctrine')->getRepository('MariusMandalUserBundle:User');
		
		// Existing username
		$existing = $em->findOneByUsername( $username );
		if( !is_null( $existing ) ) {
			throw new Exception('Username already taken', 1001);
		}

		// Existing email
		$existing = $em->findOneByEmail( $email );
		if( !is_null( $existing ) ) {
			throw new Exception('Email address already registered to another user', 1002);
		}

		$user = $this->userManager->createUser();
		$user->setUsername( $username );
		$user->setEmail( $email );
		$user->setPhone( $phone );
		$user->setPlainPassword( $password );
		$user->setActive( true );
		try {
			$this->userManager->updateUser($user);
		} catch( Exception $e ) {
			throw new Exception('User creation failed! System error: '. $e->getMessage() );
		}
		
		return $user;
    }
    
    /**
     *
     * Update an user
     * @param Int UserID
     * @param String username 
     * @param String phone
     * @param String e-mail
     * @param String password
     *
     * return User object
     */
    public function update( $userID, $username, $phone, $email ) {
		$user = $this->_getById( $userID );		
		$user->setUsername( $username );
		$user->setEmail( $email );
		$user->setPhone( $phone );
		try {
			$this->userManager->updateUser($user);
		} catch( Exception $e ) {
			throw new Exception('User update failed! System error: '. $e->getMessage() );
		}
		
		return $user;
    }
    
    public function deactivate( $userID ) {
   		$user = $this->_getById( $userID );		
   		$user->setActive( false );
   		$this->userManager->updateUser( $user );
    }
    
    public function activate( $userID ) {
   		$user = $this->_getById( $userID );		
   		$user->setActive( true );
   		$this->userManager->updateUser( $user );	    
    }
    
    private function _getById( $userID ) {
   		$em = $this->container->get('doctrine')->getRepository('MariusMandalUserBundle:User');
		
		// Existing username
		$user = $em->findOneById( $userID );
		if( is_null( $user ) ) {
			throw new Exception('User '. $userID .' does not exist!', 1051);
		}
		return $user;
    }
   }