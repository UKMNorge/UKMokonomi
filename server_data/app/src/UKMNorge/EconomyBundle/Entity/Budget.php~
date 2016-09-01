<?php

namespace UKMNorge\EconomyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Budget
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="UKMNorge\EconomyBundle\Entity\Repository\BudgetRepository")
 */
class Budget
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
     * @ORM\Column(name="Description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="Owner", type="integer", nullable=true)
     */
    private $owner=0;
 
    /**
     * @var integer
     *
     * @ORM\Column(name="Code", type="integer", nullable=true)
     */
    private $code=0;
    
	/**
     * @ORM\OneToMany(targetEntity="BudgetAllocatedAmount", mappedBy="budget")
     */
    private $allocatedAmounts;
    private $allocatedAmountsArray = false;
    private $allocatedTotalArray = false;

	/**
     * @ORM\OneToMany(targetEntity="Project", mappedBy="budget")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $projects;
	private $projectAllocatedTotalArray=false;
	/**
     * @ORM\OneToMany(targetEntity="SubProject", mappedBy="budget")
     */
    private $subProjects;    
    private $subProjectAllocatedTotalArray=false;
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
     * @return Budget
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
     * @return Budget
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
     * @return Budget
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
        $this->projects = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add projects
     *
     * @param \UKMNorge\EconomyBundle\Entity\Project $projects
     * @return Budget
     */
    public function addProject(\UKMNorge\EconomyBundle\Entity\Project $projects)
    {
        $this->projects[] = $projects;

        return $this;
    }

    /**
     * Remove projects
     *
     * @param \UKMNorge\EconomyBundle\Entity\Project $projects
     */
    public function removeProject(\UKMNorge\EconomyBundle\Entity\Project $projects)
    {
        $this->projects->removeElement($projects);
    }

    /**
     * Get projects
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * Add subProjects
     *
     * @param \UKMNorge\EconomyBundle\Entity\SubProject $subProjects
     * @return Budget
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
    
    public function getProjectsAllocatedTotal( $year ) {
	    $this->_reloadProjectsAllocatedTotalArray();
		// If there is set an amount for given year, return
	    if( isset( $this->projectAllocatedTotalArray[ $year ] ) ) {
		    return $this->projectAllocatedTotalArray[ $year ];
	    }
	    // If none allocated, zero it is
	    return 0;
    }
    
    private function _reloadProjectsAllocatedTotalArray() {
	    // If not loaded, load now
	    if( $this->projectAllocatedTotalArray == false ) {
		    foreach( $this->getProjects() as $project ) {
#			    echo $project->getName() .'<br />';
			    $allocatedAmounts = $project->getAllocatedAmounts();
				foreach( $allocatedAmounts as $entity ) {
#					echo $entity->getYear() .' => '. $entity->getAmount() .'<br />';
					// Store current amount
					if( !isset( $this->projectAllocatedTotalArray[ $entity->getYear() ] ) ) {
						$this->projectAllocatedTotalArray[ $entity->getYear() ] = 0;
					}
					$this->projectAllocatedTotalArray[ $entity->getYear() ] += $entity->getAmount();
				}
			}
		}
    }
    
    
    public function getSubProjectsAllocatedTotal( $year ) {
	    $this->_reloadSubProjectsAllocatedTotalArray();
		// If there is set an amount for given year, return
	    if( isset( $this->subProjectAllocatedTotalArray[ $year ] ) ) {
		    return $this->subProjectAllocatedTotalArray[ $year ];
	    }
	    // If none allocated, zero it is
	    return 0;
    }
    
    private function _reloadSubProjectsAllocatedTotalArray() {
	    // If not loaded, load now
	    if( $this->subProjectAllocatedTotalArray == false ) {
		    foreach( $this->getSubProjects() as $subProject ) {
#			    echo $project->getName() .'<br />';
			    $allocatedAmounts = $subProject->getAllocatedAmounts();
				foreach( $allocatedAmounts as $entity ) {
#					echo $entity->getYear() .' => '. $entity->getAmount() .'<br />';
					// Store current amount
					if( !isset( $this->subProjectAllocatedTotalArray[ $entity->getYear() ] ) ) {
						$this->subProjectAllocatedTotalArray[ $entity->getYear() ] = 0;
					}
					$this->subProjectAllocatedTotalArray[ $entity->getYear() ] += $entity->getAmount();
				}
			}
		}
    }

    /**
     * Add allocatedAmounts
     *
     * @param \UKMNorge\EconomyBundle\Entity\BudgetAllocatedAmount $allocatedAmounts
     * @return Budget
     */
    public function addAllocatedAmount(\UKMNorge\EconomyBundle\Entity\BudgetAllocatedAmount $allocatedAmounts)
    {
        $this->allocatedAmounts[] = $allocatedAmounts;

        return $this;
    }

    /**
     * Remove allocatedAmounts
     *
     * @param \UKMNorge\EconomyBundle\Entity\BudgetAllocatedAmount $allocatedAmounts
     */
    public function removeAllocatedAmount(\UKMNorge\EconomyBundle\Entity\BudgetAllocatedAmount $allocatedAmounts)
    {
        $this->allocatedAmounts->removeElement($allocatedAmounts);
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

	    // If not loaded, load now
	    if( $this->allocatedAmountsArray == false ) {
		    $allocatedAmounts = $this->getAllocatedAmounts();
			foreach( $allocatedAmounts as $entity ) {
				$this->allocatedAmountsArray[ $entity->getYear() ] = $entity->getAmount();
			}
		}
		// If there is set an amount for given year, return
	    if( isset( $this->allocatedAmountsArray[ $year ] ) ) {
		    return $this->allocatedAmountsArray[ $year ];
	    }
	    // If none allocated, zero it is
	    return 0;
    }

    /**
     * Set code
     *
     * @param integer $code
     * @return Budget
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return integer 
     */
    public function getCode()
    {
        return $this->code;
    }
}
