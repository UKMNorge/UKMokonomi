<?php

namespace UKMNorge\EconomyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AllocatedAmount
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="UKMNorge\EconomyBundle\Entity\Repository\AllocatedAmountRepository")
 */
class AllocatedAmount
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
     * @ORM\Column(name="ProjectId", type="integer")
     */
    private $projectId;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="SubProject", inversedBy="allocatedAmounts")
     * @ORM\JoinColumn(name="subProjectId", referencedColumnName="id")
     */
    private $subProjectId;

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
     * Set projectId
     *
     * @param integer $projectId
     * @return AllocatedAmount
     */
    public function setProjectId($projectId)
    {
        $this->projectId = $projectId;

        return $this;
    }

    /**
     * Get projectId
     *
     * @return integer 
     */
    public function getProjectId()
    {
        return $this->projectId;
    }

    /**
     * Set subProjectId
     *
     * @param integer $subProjectId
     * @return AllocatedAmount
     */
    public function setSubProjectId($subProjectId)
    {
        $this->subProjectId = $subProjectId;

        return $this;
    }

    /**
     * Get subProjectId
     *
     * @return integer 
     */
    public function getSubProjectId()
    {
        return $this->subProjectId;
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
}
