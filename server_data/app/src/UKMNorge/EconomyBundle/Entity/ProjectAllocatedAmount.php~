<?php

namespace UKMNorge\EconomyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProjectAllocatedAmount
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="UKMNorge\EconomyBundle\Entity\Repository\ProjectAllocatedAmountRepository")
 */
class ProjectAllocatedAmount
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
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="allocatedAmounts")
     * @ORM\JoinColumn(name="Project", referencedColumnName="id")
     */
    private $project;

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
     * Set Project
     *
     * @param \UKMNorge\EconomyBundle\Entity\Project $Project
     * @return ProjectAllocatedAmount
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
