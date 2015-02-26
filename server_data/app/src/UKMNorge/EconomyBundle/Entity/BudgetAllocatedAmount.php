<?php

namespace UKMNorge\EconomyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BudgetAllocatedAmount
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="UKMNorge\EconomyBundle\Entity\Repository\BudgetAllocatedAmountRepository")
 */
class BudgetAllocatedAmount
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
     * @ORM\ManyToOne(targetEntity="Budget", inversedBy="allocatedAmounts")
     * @ORM\JoinColumn(name="Budget", referencedColumnName="id")
     */
    private $budget;

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
     * Set Budget
     *
     * @param \UKMNorge\EconomyBundle\Entity\Budget $budget
     * @return BudgetAllocatedAmount
     */
    public function setBudget(\UKMNorge\EconomyBundle\Entity\Budget $budget = null)
    {
        $this->budget = $budget;

        return $this;
    }

    /**
     * Get Budget
     *
     * @return \UKMNorge\EconomyBundle\Entity\Budget 
     */
    public function getBudget()
    {
        return $this->budget;
    }
}
