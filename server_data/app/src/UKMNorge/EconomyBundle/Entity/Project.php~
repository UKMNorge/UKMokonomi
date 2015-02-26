<?php

namespace UKMNorge\EconomyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Project
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="UKMNorge\EconomyBundle\Entity\Repository\ProjectRepository")
 */
class Project
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Name", type="string", length=150)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="Description", type="string", length=255)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="Owner", type="integer")
     */
    private $owner;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Budget", inversedBy="projects")
     * @ORM\JoinColumn(name="Budget", referencedColumnName="id")
     */
    private $budget;

	/**
     * @ORM\OneToMany(targetEntity="SubProject", mappedBy="project")
     */
    private $subProjects;

	/**
     * @ORM\OneToMany(targetEntity="SubProjectAllocatedAmount", mappedBy="project")
     */
    private $subProjectsAllocatedAmounts;
    private $subProjectsAllocatedAmountsArray = false;
    private $subProjectsAllocatedTotalArray = false;

	/**
     * @ORM\OneToMany(targetEntity="ProjectAllocatedAmount", mappedBy="project")
     */
    private $allocatedAmounts;

	private $allocatedAmountsArray = false;

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
     * Set name
     *
     * @param string $name
     * @return Project
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Project
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set owner
     *
     * @param integer $owner
     * @return Project
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return integer 
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set owner object (PublicUser)
     *
     * @param PublicUser $ownerObject
     * @return Budget
     */
    public function setOwnerObject($ownerObject)
    {
        $this->ownerObject = $ownerObject;

        return $this;
    }
    
    /**
     * Get owner object (PublicUser)
     *
     * @return PublicUSer 
     */
    public function getOwnerObject()
    {
        return $this->ownerObject;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->subProjects = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add subProjects
     *
     * @param \UKMNorge\EconomyBundle\Entity\SubProject $subProjects
     * @return Project
     */
    public function addSubProject(\UKMNorge\EconomyBundle\Entity\SubProject $subProjects)
    {
        $this->subProjects[] = $subProjects;

        return $this;
    }

    /**
     * Remove subProjects
     *
     * @param \UKMNorge\EconomyBundle\Entity\SubProject $subProjects
     */
    public function removeSubProject(\UKMNorge\EconomyBundle\Entity\SubProject $subProjects)
    {
        $this->subProjects->removeElement($subProjects);
    }

    /**
     * Get subProjects
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSubProjects()
    {
        return $this->subProjects;
    }

    /**
     * Add subProjectsAllocatedAmounts
     *
     * @param \UKMNorge\EconomyBundle\Entity\SubProjectAllocatedAmount $subProjectsAllocatedAmounts
     * @return Project
     */
    public function addSubProjectsAllocatedAmount(\UKMNorge\EconomyBundle\Entity\SubProjectAllocatedAmount $subProjectsAllocatedAmounts)
    {
        $this->subProjectsAllocatedAmounts[] = $subProjectsAllocatedAmounts;

        return $this;
    }

    /**
     * Remove subProjectsAllocatedAmounts
     *
     * @param \UKMNorge\EconomyBundle\Entity\SubProjectAllocatedAmount $subProjectsAllocatedAmounts
     */
    public function removeSubProjectsAllocatedAmount(\UKMNorge\EconomyBundle\Entity\SubProjectAllocatedAmount $subProjectsAllocatedAmounts)
    {
        $this->subProjectsAllocatedAmounts->removeElement($subProjectsAllocatedAmounts);
    }

    /**
     * Get subProjectsAllocatedAmounts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSubProjectsAllocatedAmounts()
    {
        return $this->subProjectsAllocatedAmounts;
    }

    /**
     * Add allocatedAmounts
     *
     * @param \UKMNorge\EconomyBundle\Entity\ProjectAllocatedAmount $allocatedAmounts
     * @return Project
     */
    public function addAllocatedAmount(\UKMNorge\EconomyBundle\Entity\ProjectAllocatedAmount $allocatedAmounts)
    {
        $this->allocatedAmounts[] = $allocatedAmounts;
		$this->allocatedAmountsArray = false;
        return $this;
    }

    /**
     * Remove allocatedAmounts
     *
     * @param \UKMNorge\EconomyBundle\Entity\ProjectAllocatedAmount $allocatedAmounts
     */
    public function removeAllocatedAmount(\UKMNorge\EconomyBundle\Entity\ProjectAllocatedAmount $allocatedAmounts)
    {
        $this->allocatedAmounts->removeElement($allocatedAmounts);
		$this->allocatedAmountsArray = false;
		$this->allocatedTotalArray = false;
    }

    /**
     * Get allocatedAmounts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAllocatedAmounts()
    {
        return $this->allocatedAmounts;
    }
    
    /**
     * Set allocatedAmountsArray
     *
     * @return void
     */
    public function setAllocatedAmountsArray( $array ) {
	    $this->allocatedAmountsArray = $array;
    }
    
    /**
     * Get allocatedAmountsArray
     *
     * @return Array
     */
    public function getAllocatedAmountsArray() {
	    return $this->allocatedAmountsArray;
    }
    
    /**
     * Get allocatedAmount
     * 
     * @param integer $year
     *
     * @return integer $amount
     */

    public function getAllocatedAmount( $year ) {

	    $this->_reloadAllocatedAmountArray();
		// If there is set an amount for given year, return
	    if( isset( $this->allocatedAmountsArray[ $year ] ) ) {
		    return $this->allocatedAmountsArray[ $year ];
	    }
	    // If none allocated, zero it is
	    return 0;
    }
        
    private function _reloadAllocatedAmountArray() {
	    // If not loaded, load now
	    if( $this->allocatedAmountsArray == false ) {
		    $allocatedAmounts = $this->getAllocatedAmounts();
			foreach( $allocatedAmounts as $entity ) {
				// Store current amount
				$this->allocatedAmountsArray[ $entity->getYear() ] = $entity->getAmount();
			}
		}
    }
    
    private function _reloadSubProjectAllocatedAmountArray() {
	    // If not loaded, load now
	    if( $this->subProjectsAllocatedAmountsArray == false ) {
		    $allocatedAmounts = $this->getSubProjectsAllocatedAmounts();
			foreach( $allocatedAmounts as $entity ) {
				// Since this one is accumulating, initiate with 0 if not isset and accumulate always in loop
				if( !isset( $this->subProjectsAllocatedAmountsArray[ $entity->getYear() ] ) ) {
					$this->subProjectsAllocatedAmountsArray[ $entity->getYear() ] = 0;
				}
				$this->subProjectsAllocatedAmountsArray[ $entity->getYear() ] += $entity->getAmount();
			}
		}
	}
	
    private function _reloadSubProjectAllocatedTotalArray() {
	    // If not loaded, load now
	    if( $this->subProjectsAllocatedTotalArray == false ) {
		    $allocatedAmounts = $this->getSubProjectsAllocatedAmounts();
			foreach( $allocatedAmounts as $entity ) {
				// Since this one is accumulating, initiate with 0 if not isset and accumulate always in loop
				if( !isset( $this->subProjectsAllocatedTotalArray[ $entity->getYear() ] ) ) {
					$this->subProjectsAllocatedTotalArray[ $entity->getYear() ] = 0;
				}
				$this->subProjectsAllocatedTotalArray[ $entity->getYear() ] += $entity->getAmount();
			}
		}
	}    
    /**
	 * Get subProjectsAllocatedTotal
	 *
	 * Returns the total of allocated projects
	 *
	 * @param integer $year
	 *
	 * @return integer $total
	 */
    public function getSubProjectsAllocatedTotal( $year ) {
	    $this->_reloadSubProjectAllocatedAmountArray();
	    $this->_reloadSubProjectAllocatedTotalArray();

	    if( isset( $this->subProjectsAllocatedTotalArray[ $year ] ) ) {
		    return $this->subProjectsAllocatedTotalArray[ $year ];
	    }	    
	    return 0;
    }
    
    /**
     * Set allocatedAmountsArray
     *
     * @return void
     */
    public function setSubProjectsAllocatedAmountsArray( $array ) {
	    $this->subProjectsAllocatedAmountsArray = $array;
    }
    
    /**
     * Get allocatedAmountsArray
     *
     * @return Array
     */
    public function getSubProjectsAllocatedAmountsArray() {
	    return $this->subProjectsAllocatedAmountsArray;
    }
    
    /**
     * Get allocatedAmount
     * 
     * @param integer $year
     *
     * @return integer $amount
     */

    public function getSubProjectsAllocatedAmount( $year ) {
	    $this->_reloadSubProjectAllocatedAmountArray();
		// If there is set an amount for given year, return
	    if( isset( $this->subProjectsAllocatedAmountsArray[ $year ] ) ) {
		    return $this->subProjectsAllocatedAmountsArray[ $year ];
	    }
	    // If none allocated, zero it is
	    return 0;
    }
    
    
    public function getTransactionsTotal( $year ) {
	    return 15000;
    }

    /**
     * Set budget
     *
     * @param \UKMNorge\EconomyBundle\Entity\Budget $budget
     * @return Project
     */
    public function setBudget(\UKMNorge\EconomyBundle\Entity\Budget $budget = null)
    {
        $this->budget = $budget;

        return $this;
    }

    /**
     * Get budget
     *
     * @return \UKMNorge\EconomyBundle\Entity\Budget 
     */
    public function getBudget()
    {
        return $this->budget;
    }
}
