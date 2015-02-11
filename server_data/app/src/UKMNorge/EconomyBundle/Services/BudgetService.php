<?php
namespace UKMNorge\EconomyBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use MariusMandal\UserBundle\Entity\User;
use UKMNorge\EconomyBundle\Entity\Budget;
use Exception;
use DateTime;

class BudgetService
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
     * Get given budget
     *
     * @param integer $id
	 *
     * @return Budget
    */
   	public function get( $id ) {
		$budget = $this->repo->findOneById( $id );
		$this->_loadOwnerObject( $budget );
		return $budget;
   	}
    /**
     * Get all budgets
	 *
     * @return array
    */
   	public function getAll() {
		$budgets = $this->repo->findBy( array(), array('name' => 'ASC') );
		$this->_loadOwnerObjects( $budgets );
		return $budgets;
   	}
    
    /** **************************************************************************************** **/
	/** CREATE FUNCTIONS
    /** **************************************************************************************** **/
    /**
	 * Create budget
	 *
	 * @param string $name
	 * @param User $owner
	 *
	 * @return Budget
	*/
    public function create( $name, $owner, $description='' ) {
		if( empty( $name ) ) {
			throw new Exception('Budget name cannot be empty!', 2000);
		}
		
		$this->_validateOwner( $owner );
		
		// Existing name
		$existing = $this->repo->findOneByName( $name );
		if( !is_null( $existing ) ) {
			throw new Exception('Budget name already registered!', 2001);
		}
		
		$budget = new Budget();
		$budget->setName( $name );
		$budget->setOwner( $owner->getId() );
		$budget->setDescription( $description );
		$this->_persistAndFlush( $budget );

		return $budget;
    }
    
    /** **************************************************************************************** **/
	/** SET FUNCTIONS
    /** **************************************************************************************** **/

    /**
	 * Set budget data
	 *
	 * @param Budget
	 * @param string Name
	 * @param User $owner
	 * @param string Description
	 * @return Budget
	*/
	public function setData( $budget, $name, $owner, $description ) {
		$this->setName( $budget, $name );
		$this->setOwner( $budget, $owner );
		$this->setDescription( $budget, $description );
		return $budget;
	}
    /**
	 * Set budget name
	 *
	 * @param Budget
	 * @param string Name
	 *
	 * @return Budget
	*/
    public function setName( $budget, $name ) {
	    if( empty( $name ) ) {
			throw new Exception('Budget name cannot be empty!', 2000);
	    }
	    
	    $this->_validate( $budget );
	    
	    $budget->setName( $name );
	    $this->_persistAndFlush( $budget );
	    return $budget;
    }

    /**
	 * Set budget description
	 *
	 * @param Budget
	 * @param string Description
	 *
	 * @return Budget
	*/
    public function setDescription( $budget, $description ) {
	    $this->_validate( $budget );	    
		$budget->setDescription( $description );
	    $this->_persistAndFlush( $budget );
	    return $budget;
    } 
    
    /**
	 * Set budget owner
	 *
	 * @param Budget
	 * @param User $owner
	 *
	 * @return Budget
	*/
    public function setOwner( $budget, $owner ) {
	    $this->_validate( $budget );
	    $this->_validateOwner( $owner );
		$budget->setOwner( $owner->getId() );
	    $this->_persistAndFlush( $budget );
	    return $budget;
    }


    /** **************************************************************************************** **/
	/** LOADERS */
    /** **************************************************************************************** **/

    /**
	 * Loop given budgets and load owner objects
	 *
	 * @return void
	*/
	private function _loadOwnerObjects( $budgets ) {
		foreach( $budgets as $budget ) {
			$this->_loadOwnerObject( $budget );
		}
	}

    /**
	 * Load owner (public) data into budget object
	 *
	 * @return void
	*/	
	private function _loadOwnerObject( $budget ) {
		if( !$this->userRepo ) {
			$this->userRepo = $this->container->get('doctrine')->getRepository('MariusMandalUserBundle:User');
		}

		$user = $this->userRepo->findOneById( $budget->getOwner() )->getPublicUser();
		$budget->setOwnerObject( $user );
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
		$this->repo	 	= $this->doctrine->getRepository('UKMecoBundle:Budget');
    }
   	
   	private function _validateOwner( $owner ) {
		if( !is_object( $owner ) || get_class( $owner ) != 'MariusMandal\UserBundle\Entity\User' ) {
			throw new Exception('Owner must be user object! Given '. ( is_object( $owner ) ? get_class( $owner ) : 'invalid object' ), 2002);
		}
		return true;
   	}

    
	private function _validate( $budget ) {
		if( get_class( $budget ) != 'UKMNorge\EconomyBundle\Entity\Budget') {
			throw new Exception('Invalid budget object!');
		}
	}
    /**
	 * Private helper: Persist budget
	 *
	 * @param Budget
	 *
	 * @return Budget
	*/

    private function _persistAndFlush( $budget ) {
   		$this->em->persist( $budget );
        try {
	        $this->em->flush();
	    } catch( Exception $e ) {
		    throw new Exception( 'Unknown EM error: '. $e->getCode() .' - '. $e->getMessage() );
	    }
		return $budget;
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
		$test_budget = $this->testHelperGet();
		if( !is_null( $test_budget ) ) {
			$this->em->remove( $test_budget );
	        $this->em->flush();
		}
		$test_budget_2 = $this->repo->findOneByName('TestBudsjett2');
		if( !is_null( $test_budget_2 ) ) {
			$this->em->remove( $test_budget_2 );
			$this->em->flush();
		}
    }
    
    /**
	 * Test helper to fetch testBudget object
	 *
	 * @return Budget
	*/
    public function testHelperGet() {
   		return $this->repo->findOneByName( 'TestBudsjett' );
    }
}