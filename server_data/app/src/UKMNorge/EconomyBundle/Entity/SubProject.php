<?php

namespace UKMNorge\EconomyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SubProject
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="UKMNorge\EconomyBundle\Entity\Repository\SubProjectRepository")
 */
class SubProject
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
     * @ORM\Column(name="Project", type="integer")
     */
    private $project;

    /**
     * @var integer
     *
     * @ORM\Column(name="Budget", type="integer")
     */
    private $budget;

	/**
     * @ORM\OneToMany(targetEntity="AllocatedAmount", mappedBy="subProjectId")
     */
    private $allocatedAmounts;

	private $allocatedAmountsArray;
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
     * @return SubProject
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
     * @return SubProject
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
     * Set project
     *
     * @param integer $project
     * @return SubProject
     */
    public function setProject($project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return integer 
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set budget
     *
     * @param integer $budget
     * @return SubProject
     */
    public function setBudget($budget)
    {
        $this->budget = $budget;

        return $this;
    }

    /**
     * Get budget
     *
     * @return integer 
     */
    public function getBudget()
    {
        return $this->budget;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->allocatedAmounts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->allocatedAmountsArray = array();
    }

    /**
     * Add allocatedAmounts
     *
     * @param \UKMNorge\EconomyBundle\Entity\AllocatedAmount $allocatedAmounts
     * @return SubProject
     */
    public function addAllocatedAmount(\UKMNorge\EconomyBundle\Entity\AllocatedAmount $allocatedAmounts)
    {
        $this->allocatedAmounts[] = $allocatedAmounts;

        return $this;
    }

    /**
     * Remove allocatedAmounts
     *
     * @param \UKMNorge\EconomyBundle\Entity\AllocatedAmount $allocatedAmounts
     */
    public function removeAllocatedAmount(\UKMNorge\EconomyBundle\Entity\AllocatedAmount $allocatedAmounts)
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
    
    public function setAllocatedAmountsArray( $array ) {
	    $this->allocatedAmountsArray = $array;
    }
    
    public function getAllocatedAmountsArray() {
	    return $this->allocatedAmountsArray;
    }
    
    public function getAllocatedAmount( $year ) {
	    if( isset( $this->allocatedAmountsArray[ $year ] ) ) {
		    return $this->allocatedAmountsArray[ $year ];
	    }
	    return 0;
    }
}
