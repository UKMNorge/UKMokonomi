<?php

namespace UKMNorge\EconomyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Transaction
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="UKMNorge\EconomyBundle\Entity\Repository\TransactionRepository")
 */
class Transaction
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
     * @var string
     *
     * @ORM\Column(name="Type", type="string", length=15)
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="Amount", type="integer")
     */
    private $amount;

    /**
     * @var integer
     *
     * @ORM\Column(name="SubProject", type="integer")
     */

    private $subProject;    /**
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
     * @var \DateTime
     *
     * @ORM\Column(name="Date", type="date")
     */
    private $date;
    
    /**
     * @var string
     *
     * @ORM\Column(name="Bilag", type="integer")
     */
    private $bilag;

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
     * @return Transaction
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
     * @return Transaction
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
     * Set type
     *
     * @param string $type
     * @return Transaction
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     * @return Transaction
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return integer 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set budget
     *
     * @param integer $budget
     * @return Transaction
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
     * Set project
     *
     * @param integer $project
     * @return Transaction
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
     * Set subProject
     *
     * @param integer $subProject
     * @return Transaction
     */
    public function setSubProject($subProject)
    {
        $this->subProject = $subProject;

        return $this;
    }

    /**
     * Get subProject
     *
     * @return integer 
     */
    public function getSubProject()
    {
        return $this->subProject;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Transaction
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set bilag
     *
     * @param integer $bilag
     * @return Transaction
     */
    public function setBilag($bilag)
    {
        $this->bilag = $bilag;

        return $this;
    }

    /**
     * Get bilag
     *
     * @return integer 
     */
    public function getBilag()
    {
        return $this->bilag;
    }
}
