<?php
namespace UKMNorge\EconomyBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use MariusMandal\UserBundle\Entity\User;
use UKMNorge\EconomyBundle\Entity\Budget;
use UKMNorge\EconomyBundle\Entity\Project;
use UKMNorge\EconomyBundle\Entity\SubProject;
use UKMNorge\EconomyBundle\Entity\Transaction;
use Exception;
use DateTime;

class TransactionService
{
    /**
     *
     * @var ContainerInterface 
     */
    protected $container;

    /**
     *
     * @var DoctrineSomething 
     */
    protected $doctrine;

    /**
     *
     * @var EntityManager 
     */
    protected $em;

    /**
     *
     * @var Repository 
     */
    protected $repo;
    
    /**
     *
     * @var Repository 
     */
    protected $userRepo;    
    
    /** **************************************************************************************** **/
	/** GETTERS
    /** **************************************************************************************** **/
    /**
     * Get all Transaction
	 *
     * @return array ?
    */
   	public function get( $id ) {
		$transaction = $this->repo->findOneById( $id );
		return $transaction;
   	}
    /**
     * Get all Transaction
	 *
     * @return array ?
    */
   	public function getAll( $subProject ) {
	   	$this->_validateSubProject( $subProject );
		$transactions = $this->repo->getAllBySubProject( $subProject, false );
		return $transactions;
   	}
   	
   	
   	public function getTotalByBudget( $budget, $year ) {
		return $this->repo->getTotalByBudget( $budget, $year );
   	}

   	public function getTotalByProject( $project, $year ) {
		return $this->repo->getTotalByProject( $project, $year );
   	}

   	public function getTotalBySubProject( $subProject, $year ) {
		return $this->repo->getTotalBySubProject( $subProject, $year );
   	}
    
    /** **************************************************************************************** **/
	/** CREATE FUNCTIONS
    /** **************************************************************************************** **/
    /**
	 * Create Transaction
	 *
	 * @param string $name
	 * @param string type
	 * @param integer amount
	 * @param SubProject object
	 * @param string description
	 * @param date date
	 *
	 * @return Transaction
	*/
    public function create( $name, $type, $amount, $subProject, $description='', $date=false ) {
	    
		if( empty( $name ) ) {
			throw new Exception('SubProject name cannot be empty!', 2000);
		}

		$this->_validateParamType( $type );
		
		if( empty( $amount ) ) {
			throw new Exception('SubProject name cannot be empty!', 2000);
		}
		
		
		if( !$date ) {
			$date = new DateTime();
		}

		$this->_validateSubProject( $subProject );

		$projectRepo = $this->doctrine->getRepository('UKMecoBundle:Project');
		$project = $projectRepo->findOneById( $subProject->getProject() );
		$this->_validateProject( $project );
				
		$budgetRepo = $this->doctrine->getRepository('UKMecoBundle:Budget');
		$budget = $budgetRepo->findOneById( $project->getBudget() );
		$this->_validateBudget( $budget );

		$transaction = new Transaction();
		$transaction->setName( $name );
		$transaction->setDescription( $description );
		$transaction->setType( $type );
		$transaction->setAmount( $amount );
		$transaction->setSubProject( $subProject->getId() );
		$transaction->setProject( $project->getId() );
		$transaction->setBudget( $budget->getId() );
		$transaction->setDate( $date );
		$this->_persistAndFlush( $transaction );

		return $transaction;
    }
    
    /** **************************************************************************************** **/
	/** SET FUNCTIONS
    /** **************************************************************************************** **/

    /**
	 * Set Transaction data
	 *
	 * @param string $name
	 * @param string type
	 * @param integer amount
	 * @param SubProject object
	 * @param string description
	 * @param date date
	 *
	 * @return Transaction
	*/
	public function setData( $transaction, $name, $type, $amount, $subProject, $description='', $date=false ) {
		$this->setName( $transaction, $name );
		$this->setType( $transaction, $type );
		$this->setAmount( $transaction, $amount );
		$this->setSubProject( $transaction, $subProject );
		$this->setDescription( $transaction, $description );
		$this->setDate( $transaction, $date );
		
		$projectRepo = $this->doctrine->getRepository('UKMecoBundle:Project');
		$project = $projectRepo->findOneById( $subProject->getProject() );
		$this->_validateProject( $project );

		$this->setProject( $transaction, $project );
				
		$budgetRepo = $this->doctrine->getRepository('UKMecoBundle:Budget');
		$budget = $budgetRepo->findOneById( $project->getBudget() );
		$this->_validateBudget( $budget );
		
		$this->setBudget( $transaction, $budget );
		
		return $transaction;
	}

    /**
	 * Set Transaction name
	 *
	 * @param Transaction
	 * @param string Name
	 *
	 * @return Transaction
	*/
    public function setName( $transaction, $name ) {
	    if( empty( $name ) ) {
			throw new Exception('Transaction name cannot be empty!', 2000);
	    }
	    
	    $this->_validate( $transaction );
	    
	    $transaction->setName( $name );
	    $this->_persistAndFlush( $transaction );
	    return $transaction;
    }

    /**
	 * Set Transaction type
	 *
	 * @param Transaction
	 * @param string Type
	 *
	 * @return Transaction
	*/
    public function setType( $transaction, $type ) {
		$this->_validateParamType( $type );
	    $this->_validate( $transaction );
	    
	    $transaction->setType( $type );
	    $this->_persistAndFlush( $transaction );
	    return $transaction;
    }

    /**
	 * Set Transaction amount
	 *
	 * @param Transaction
	 * @param integer amount
	 *
	 * @return Transaction
	*/
    public function setAmount( $transaction, $amount ) {
	    $this->_validate( $transaction );
	    
	    $transaction->setAmount( $amount );
	    $this->_persistAndFlush( $transaction );
	    return $transaction;
    }
    
    /**
	 * Set Transaction SubProject
	 *
	 * @param Transaction
	 * @param SubProject
	 *
	 * @return Transaction
	*/
    public function setSubProject( $transaction, $subProject ) {
	    $this->_validate( $transaction );
		$this->_validateSubProject( $subProject );
		
		$transaction->setSubProject( $subProject->getId() );
	    $this->_persistAndFlush( $transaction );
	    return $transaction;
    }
    
    /**
	 * Set Transaction Project
	 *
	 * @param Transaction
	 * @param Project
	 *
	 * @return Transaction
	*/
    public function setProject( $transaction, $project ) {
	    $this->_validate( $transaction );
		$this->_validateProject( $project );
		
		$transaction->setProject( $project->getId() );
	    $this->_persistAndFlush( $transaction );
	    return $transaction;
    }

   /**
	 * Set Transaction Budget
	 *
	 * @param Transaction
	 * @param Budget
	 *
	 * @return Transaction
	*/
    public function setBudget( $transaction, $budget ) {
	    $this->_validate( $transaction );
		$this->_validateBudget( $budget );
		$transaction->setBudget( $budget->getId() );
	    $this->_persistAndFlush( $transaction );
	    return $transaction;
    }

    /**
	 * Set Transaction description
	 *
	 * @param Transaction
	 * @param string Description
	 *
	 * @return Transaction
	*/
    public function setDescription( $transaction, $description ) {
	    $this->_validate( $transaction );	    
		$transaction->setDescription( $description );
	    $this->_persistAndFlush( $transaction );
	    return $transaction;
    } 
    
    /**
	 * Set Transaction date
	 *
	 * @param Transaction
	 * @param date
	 *
	 * @return Transaction
	*/
    public function setDate( $transaction, $date ) {
	    $this->_validate( $transaction );
	    if( !$date ) {
			$date = new DateTime();
		}
		$transaction->setDate( $date );
	    $this->_persistAndFlush( $transaction );
	    return $transaction;
    } 

    /** **************************************************************************************** **/
	/** PARAMETER VALIDATORS */
    /** **************************************************************************************** **/
	private function _validateParamType( $type ) {
		if( empty( $type ) ) {
			throw new Exception('Transaction type name cannot be empty!', 2000);
		} else {
			if( !in_array( $type, array('Budget','Accounting') ) ) {
				throw new Exception('Given invalid transaction type! ('. $type .')', 2003);
			}
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
		$this->repo	 	= $this->doctrine->getRepository('UKMecoBundle:Transaction');
    }
    
    private function _validateSubProject( $subProject ) {
   		if( !is_object( $subProject ) || get_class( $subProject ) != 'UKMNorge\EconomyBundle\Entity\SubProject' ) {
			throw new Exception('SubProject must be object of type SubProject! Given '. ( is_object( $project ) ? get_class( $project ) : 'invalid object' ), 2002);
		}
		return true;
    }    
    private function _validateProject( $project ) {
   		if( !is_object( $project ) || get_class( $project ) != 'UKMNorge\EconomyBundle\Entity\Project' ) {
			throw new Exception('Project must be object of type Project! Given '. ( is_object( $project ) ? get_class( $project ) : 'invalid object' ), 2002);
		}
		return true;
    }
   	
   	private function _validateBudget( $budget ) {
		if( !is_object( $budget ) || get_class( $budget ) != 'UKMNorge\EconomyBundle\Entity\Budget' ) {
			throw new Exception('Budget must be object of type Budget! Given '. ( is_object( $budget ) ? get_class( $budget ) : 'invalid object' ), 2002);
		}
		return true;
   	}
    
	private function _validate( $transaction ) {
		if( get_class( $transaction ) != 'UKMNorge\EconomyBundle\Entity\Transaction') {
			throw new Exception('Invalid Transaction object!');
		}
	}
    /**
	 * Private helper: Persist SubProject
	 *
	 * @param SubProject
	 *
	 * @return SubProject
	*/

    private function _persistAndFlush( $transaction ) {
   		$this->em->persist( $transaction );
        try {
	        $this->em->flush();
	    } catch( Exception $e ) {
		    throw new Exception( 'Unknown EM error: '. $e->getCode() .' - '. $e->getMessage() );
	    }
		return $transaction;
    }
    
    /** **************************************************************************************** **/
	/** CLASS TEST HELPERS
    /** **************************************************************************************** **/
    
    /**
	 * Test helper to reset testing data
	 *
	 * @return void
	*/
    public function testHelperReset() {
		$test_transaction = $this->testHelperGet();
		if( !is_null( $test_transaction ) ) {
			$this->em->remove( $test_transaction );
	        $this->em->flush();
		}
    }
    
    /**
	 * Test helper to fetch object
	 *
	 * @return Transaction
	*/
    public function testHelperGet() {
   		$transaction = $this->repo->findOneByName( 'TestTransaksjon' );
		if( is_null( $transaction ) ) {
			$transaction = $this->repo->findOneByName( 'TestTransaksjon2' );
		}
		return $transaction;
    }
}