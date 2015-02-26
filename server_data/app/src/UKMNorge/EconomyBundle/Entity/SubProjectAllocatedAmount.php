<?php

namespace UKMNorge\EconomyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SubProjectAllocatedAmount
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="UKMNorge\EconomyBundle\Entity\Repository\SubProjectAllocatedAmountRepository")
 */
class SubProjectAllocatedAmount
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
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="subProjectsAllocatedAmounts")
     * @ORM\JoinColumn(name="Project", referencedColumnName="id")
     */
    private $project;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="SubProject", inversedBy="allocatedAmounts")
     * @ORM\JoinColumn(name="subProject", referencedColumnName="id")
     */
    private $subProject;

    /**
     * @var integer
     *
     * @ORM\Column(name="Amount", type="integer")
     */
    private $amount;

    /**
     * @var integer
     *
     * @ORM\Column(name="Year", type="integer")
     */
    private $year;


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
     * Set amount
     *
     * @param integer $amount
     * @return AllocatedAmount
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
     * Set year
     *
     * @param integer $year
     * @return AllocatedAmount
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return integer 
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set subProject
     *
     * @param \UKMNorge\EconomyBundle\Entity\SubProject $subProject
     * @return SubProjectAllocatedAmount
     */
    public function setSubProject(\UKMNorge\EconomyBundle\Entity\SubProject $subProject = null)
    {
        $this->subProject = $subProject;

        return $this;
    }

    /**
     * Get subProject
     *
     * @return \UKMNorge\EconomyBundle\Entity\SubProject 
     */
    public function getSubProject()
    {
        return $this->subProject;
    }

    /**
     * Set project
     *
     * @param \UKMNorge\EconomyBundle\Entity\Project $project
     * @return SubProjectAllocatedAmount
     */
    public function setProject(\UKMNorge\EconomyBundle\Entity\Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \UKMNorge\EconomyBundle\Entity\Project 
     */
    public function getProject()
    {
        return $this->project;
    }
}
