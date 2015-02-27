<?php
namespace UKMNorge\EconomyBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use MariusMandal\UserBundle\Entity\User;
use UKMNorge\EconomyBundle\Entity\Budget;
use Exception;
use DateTime;

class AmountService
{
    /**
     * @var ContainerInterface 
     */
    protected $container;

    /**
     * @var DoctrineSomething 
     */
    protected $doctrine;

    /**
     * @var EntityManager 
     */
    protected $em;

    
    /** **************************************************************************************** **/
	/** GET TRANSACTION DETAILS
    /** **************************************************************************************** **/
	
	/**
	 * getTransactionTotalByBudget
	 *
	 * Summarizes all transactions for given budget in given year
	 *
	 * @param UKMNorge\EconomyBundle\Entity\Budget $budget
	 * @param integer $year
	 *
	 * @return integer $amount
	 */
	public function getTransactionTotalByBudget( $budget, $year ) {
		$this->_validateBudget( $budget );
		$this->_validateYear( $year );
		
		return $this->transactionRepo->getTotalByBudget( $budget, $year );
	}

	/**
	 * getTransactionTotalByProject
	 *
	 * Summarizes all transactions for given budget in given year
	 *
	 * @param UKMNorge\EconomyBundle\Entity\Budget $budget
	 * @param integer $year
	 *
	 * @return integer $amount
	 */
	public function getTransactionTotalByProject( $project, $year ) {
		$this->_validateProject( $project );
		$this->_validateYear( $year );
		
		return $this->transactionRepo->getTotalByProject( $project, $year );
	}


    /** **************************************************************************************** **/
	/** GET ALLOCATED DETAILS
    /** **************************************************************************************** **/
	/**
	 * getTotalAllocatedByProject
	 *
	 * Returns allocated amount for given project in given year
	 *
	 * @param UKMNorge\EconomyBundle\Entity\Project $project
	 * @param integer $year
	 *
	 * @return integer $amount
	 */
	public function getTotalAllocatedByProject( $project, $year ) {
		$this->_validateProject( $project );
		$this->_validateYear( $year );

		return $this->projectAllocatedAmountRepo->getTotalByProject( $project, $year );
	}

    /** **************************************************************************************** **/
	/** GET ACCUMULATED DETAILS
    /** **************************************************************************************** **/
	/**
	 * getTotalAccumulatedSubProjectsByBudget
	 *
	 * Returns accumulated amount for all subprojects in budget in given year
	 *
	 * @param UKMNorge\EconomyBundle\Entity\Budget $budget
	 * @param integer $year
	 *
	 * @return integer $amount
	 */
	public function getTotalAccumulatedSubProjectsByBudget( $budget, $year ) {
		$this->_validateBudget( $budget );
		$this->_validateYear( $year );

		return $this->subProjectAllocatedAmountRepo->getTotalByBudget( $budget, $year );
	}


    /** **************************************************************************************** **/
	/** VALIDATORS
    /** **************************************************************************************** **/

   	private function _validateOwner( $owner ) {
		if( !is_object( $owner ) || get_class( $owner ) != 'MariusMandal\UserBundle\Entity\User' ) {
			throw new Exception('Owner must be user object! Given '. ( is_object( $owner ) ? get_class( $owner ) : 'invalid object' ), 2002);
		}
		return true;
   	}

    
	private function _validateBudget( $budget ) {
		if( get_class( $budget ) != 'UKMNorge\EconomyBundle\Entity\Budget') {
			throw new Exception('Invalid budget object!');
		}
		return true;
	}

    private function _validateProject( $project ) {
   		if( !is_object( $project ) || get_class( $project ) != 'UKMNorge\EconomyBundle\Entity\Project' ) {
			throw new Exception('Owner must be user object! Given '. ( is_object( $project ) ? get_class( $project ) : 'invalid object' ), 2002);
		}
		return true;
    }
    
	private function _validateSubProject( $subProject ) {
		if( get_class( $subProject ) != 'UKMNorge\EconomyBundle\Entity\SubProject') {
			throw new Exception('Invalid SubProject object!');
		}
		return true;
	}

	private function _validateYear( $year ) {
		if( !is_numeric( $year ) || strlen( $year ) != 4) {
			throw new Exception('Year must be integer!');
		}
		return true;
	}
    /** **************************************************************************************** **/
	/** CLASS INTERNALS AND HELPERS
    /** **************************************************************************************** **/
	/**
	 * 
	 * Class constructor
	 * @param ContainerInterface
	 *
	*/
    public function __construct( ContainerInterface $container ) {
        $this->container = $container;
        
		$this->doctrine = $this->container->get('doctrine');
		$this->em 		= $this->doctrine->getManager();
		$this->budgetRepo		= $this->doctrine->getRepository('UKMecoBundle:Budget');
		$this->projectRepo		= $this->doctrine->getRepository('UKMecoBundle:Project');
		$this->subProjectRepo	= $this->doctrine->getRepository('UKMecoBundle:SubProject');
		$this->projectAllocatedAmountRepo		= $this->doctrine->getRepository('UKMecoBundle:ProjectAllocatedAmount');
		$this->subProjectAllocatedAmountRepo	= $this->doctrine->getRepository('UKMecoBundle:SubProjectAllocatedAmount');
		$this->transactionRepo	= $this->doctrine->getRepository('UKMecoBundle:Transaction');

		
    }
}