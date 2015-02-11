<?php
// src/Acme/UserBundle/Entity/User.php

namespace MariusMandal\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var string
     *
     * @ORM\Column(name="Phone", type="string", length=12, nullable=true)
     */
    private $phone;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="Firstname", type="string", length=50, nullable=true)
	 */
	private $firstname;
	
	/**
	 * @var string
	 *
	 * @ORM\Column(name="Lastname", type="string", length=50, nullable=true)
	 */
	private $lastname;
	
	/**
	 * @var object publicUser
	 *
	 */	
	private $publicUser;
	
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set phone
     *
     * @param string $phone
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return User
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
		if( empty( $this->firstname ) && empty( $this->lastname ) ) {
			return $this->getUsername();
		}

        return $this->lastname;
    }
    
    /**
     * Get name (firstname ~' '~ lastname)
     *
     * @return string
     */
    public function getName() {
	    return $this->getFirstname() .' '. $this->getLastname();
    }
    
	/**
	 * Get an object with public user data
	 *
	 * @return object
	 */
	public function getPublicUser() {
		$this->_loadPublicUser();
		return $this->publicUser;
	}
	
	private function _loadPublicUser() {
		$this->publicUser = new PublicUser( $this );
	}
}

class PublicUser {
	var $ID;
	var $username;
	var $firstname;
	var $lastname;
	var $phone;
	var $email;
	public function __construct( $userObject ) {
		$this->ID = $userObject->getId();
		$this->username = $userObject->getUsername();
		$this->firstname = $userObject->getFirstname();
		$this->lastname = $userObject->getLastname();
		$this->phone = $userObject->getPhone();
		$this->email = $userObject->getEmail();
	}
	
    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }
    
    /**
     * Get name (firstname ~' '~ lastname)
     *
     * @return string
     */
    public function getName() {
	    return $this->getFirstname() .' '. $this->getLastname();
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

}
