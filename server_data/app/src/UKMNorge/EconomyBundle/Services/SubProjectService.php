<?php
namespace UKMNorge\EconomyBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use MariusMandal\UserBundle\Entity\User;
use UKMNorge\EconomyBundle\Entity\Budget;
use UKMNorge\EconomyBundle\Entity\Project;
use UKMNorge\EconomyBundle\Entity\SubProject;
use UKMNorge\EconomyBundle\Entity\SubProjectAllocatedAmount;
use Exception;
use DateTime;

class SubProjectService
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
     * Get all SubProjects
	 *
     * @return array ?
    */
   	public function get( $id ) {
		$subProject = $this->repo->findOneById( $id );
		return $subProject;
   	}
    /**
     * Get all SubProjects
	 *
     * @return array ?
    */
   	public function getAll( $project, $allocatedAmountData=false ) {
	   	$this->_validateProject( $project );
		$subProjects = $this->repo->findBy( array('project'=>$project->getId()), array('name' => 'ASC') );
		
		if( $allocatedAmountData !== false ) {
			$this->_loadAllocatedAmounts( $subProjects, $allocatedAmountData );
		}
		return $subProjects;
   	}
    
    /** **************************************************************************************** **/
	/** CREATE FUNCTIONS
    /** **************************************************************************************** **/
    /**
	 * Create SubProject
	 *
	 * @param string $name
	 * @param User $owner
	 *
	 * @return SubProject
	*/
    public function create( $name, $project, $description='' ) {
	    
		if( empty( $name ) ) {
			throw new Exception('SubProject name cannot be empty!', 2000);
		}

		$this->_validateProject( $project );
		// Existing name
		$existing = $this->repo->findOneByName( array('name' => $name, 'project' => $project->getId() ) );
		if( !is_null( $existing ) ) {
			throw new Exception('SubProject name already registered within project ("'.$project->getName().'::'. $name.'")!', 2001);
		}
		
		$budgetRepo = $this->doctrine->getRepository('UKMecoBundle:Budget');
		$budget = $budgetRepo->findOneById( $project->getBudget() );
		$this->_validateBudget( $budget );

		$subProject = new SubProject();
		$subProject->setName( $name );
		$subProject->setProject( $project);
		$subProject->setBudget( $budget );
		$subProject->setDescription( $description );
		$this->_persistAndFlush( $subProject );

		return $subProject;
    }
    
    /** **************************************************************************************** **/
	/** SET FUNCTIONS
    /** **************************************************************************************** **/

    /**
	 * Set SubProject data
	 *
	 * @param SubProject
	 * @param string Name
	 * @param User $owner
	 * @param string Description
	 * @return SubProject
	*/
	public function setData( $subProject, $name, $project, $description ) {
		$this->setName( $subProject, $name );
		$this->setProject( $subProject, $project );
		
		$budgetRepo = $this->doctrine->getRepository('UKMecoBundle:Budget');
		$budget = $budgetRepo->findOneById( $project->getBudget() );
		$this->_validateBudget( $budget );
		
		$this->setBudget( $subProject, $budget );
		$this->setDescription( $subProject, $description );
		return $subProject;
	}
    /**
	 * Set SubProject name
	 *
	 * @param SubProject
	 * @param string Name
	 *
	 * @return SubProject
	*/
    public function setName( $subProject, $name ) {
	    if( empty( $name ) ) {
			throw new Exception('SubProject name cannot be empty!', 2000);
	    }
	    
	    $this->_validate( $subProject );
	    
	    $subProject->setName( $name );
	    $this->_persistAndFlush( $subProject );
	    return $subProject;
    }

    /**
	 * Set SubProject description
	 *
	 * @param SubProject
	 * @param string Description
	 *
	 * @return SubProject
	*/
    public function setDescription( $subProject, $description ) {
	    $this->_validate( $subProject );	    
		$subProject->setDescription( $description );
	    $this->_persistAndFlush( $subProject );
	    return $subProject;
    } 
    
    /**
	 * Set SubProject owner
	 *
	 * @param SubProject
	 * @param User $owner
	 *
	 * @return SubProject
	*/
    public function setProject( $subProject, $project ) {
	    $this->_validate( $subProject );
		$this->_validateProject( $project );
		
		$subProject->setProject( $project );
	    $this->_persistAndFlush( $subProject );
	    return $subProject;
    }

    /**
	 * Set SubProject budget
	 *
	 * @param SubProject
	 * @param Budget $budget
	 *
	 * @return SubProject
	*/
    public function setBudget( $subProject, $budget ) {
	    $this->_validate( $subProject );
		$this->_validateBudget( $budget );
		$subProject->setBudget( $budget );
	    $this->_persistAndFlush( $subProject );
	    return $subProject;
    }

    /** **************************************************************************************** **/
	/** ALLOCATED AMOUNT FUNCTIONS
    /** **************************************************************************************** **/

    /**
	 * Set SubProject data
	 *
	 * @param SubProject
	 * @param integer year
	 * @param integer amount
	 * @return AllocatedAmount
	*/
	public function setAllocatedAmount( $subProject, $year, $amount ) {
		$this->_validate( $subProject );
	
		if( !is_numeric( $year ) || !is_integer( $year ) ) {
			throw new Exception('Given year is not an integer! Given '. gettype( $year ). ' "'. $year .'"' );
		}
		if( !is_numeric( $amount ) || !is_integer( $amount ) ) {
			throw new Exception('Given amount is not an integer! Given '. gettype( $amount ). ' "'. $amount .'"' );
		}
		$allocatedAmountRepo = $this->doctrine->getRepository('UKMecoBundle:SubProjectAllocatedAmount');
		
		$allocatedAmount = $allocatedAmountRepo->findOneBy( array('subProject' => $subProject,
																 'year' => $year ) );
		if( is_null( $allocatedAmount ) ) {
			$allocatedAmount = new SubProjectAllocatedAmount();
			$allocatedAmount->setProject( $subProject->getProject() );
			$allocatedAmount->setSubProject( $subProject );
			$allocatedAmount->setYear( $year );
		}
		
		// TODO: Delete from DB if amount == 0
		
		$allocatedAmount->setAmount( $amount );
		
		$this->_persistAndFlush( $allocatedAmount );
		
		return $allocatedAmount;
	}
	
	public function getAllocatedAmounts( $subProject, $startYear=null, $stopYear=null ) {
		$this->_validate( $subProject );
		
		$allocatedAmounts = $subProject->getAllocatedAmounts();

		$array = array();
		if( !is_null( $startYear ) && !is_null( $stopYear ) ) {
			for( $i=$startYear; $i<$stopYear+1; $i++) {
				$array[ $i ] = 0;
			}
		}
		
		foreach( $allocatedAmounts as $entity ) {
			$array[ $entity->getYear() ] = $entity->getAmount();
		}
		return $array;
	}

    /** **************************************************************************************** **/
	/** LOADERS */
    /** **************************************************************************************** **/
	private function _loadAllocatedAmounts( $subProjects, $allocatedAmountData ) {
		if( $allocatedAmountData === true ) {
			$startYear = null;
			$stopYear = null;
		} else {
			if( !isset( $allocatedAmountData->startYear ) || !isset( $allocatedAmountData->stopYear ) ) {
				throw new Exception('AllocatedAmountData must be stdClass with integer startYear and integer stopYear defined!');
			}
			if( !is_integer( $allocatedAmountData->startYear ) ) {
				throw new Exception('AllocatedAmountData->startYear must be integer!');
			}
			if( !is_integer( $allocatedAmountData->stopYear ) ) {
				throw new Exception('AllocatedAmountData->stopYear must be integer!');
			}
			if( $allocatedAmountData->startYear > $allocatedAmountData->stopYear ) {
				throw new Exception('AllocatedAmountData->stopYear must be smaller than AllocatedAmountData->startYear!');
			}
			$startYear = $allocatedAmountData->startYear;
			$stopYear = $allocatedAmountData->stopYear;
		}
		
		foreach( $subProjects as $subProject ) {
			$subProject->setAllocatedAmountsArray( $this->getAllocatedAmounts( $subProject, $startYear, $stopYear ) );
		}
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
		$this->repo	 	= $this->doctrine->getRepository('UKMecoBundle:SubProject');
		
		$this->environment = $this->container->get( 'kernel' )->getEnvironment();
    }
    
    private function _validateProject( $project ) {
   		if( !is_object( $project ) || get_class( $project ) != 'UKMNorge\EconomyBundle\Entity\Project' ) {
			throw new Exception('Owner must be user object! Given '. ( is_object( $project ) ? get_class( $project ) : 'invalid object' ), 2002);
		}
		return true;
    }
   	
   	private function _validateBudget( $budget ) {
		if( !is_object( $budget ) || get_class( $budget ) != 'UKMNorge\EconomyBundle\Entity\Budget' ) {
			throw new Exception('Budget must be budget object! Given '. ( is_object( $budget ) ? get_class( $budget ) : 'invalid object' ), 2002);
		}
		return true;
   	}
    
	private function _validate( $subProject ) {
		if( get_class( $subProject ) != 'UKMNorge\EconomyBundle\Entity\SubProject') {
			throw new Exception('Invalid SubProject object!');
		}
	}
    /**
	 * Private helper: Persist Entity
	 *
	 * @param Entity
	 *
	 * @return Entity
	*/

    private function _persistAndFlush( $entity ) {
   		$this->em->persist( $entity );
        try {
	        $this->em->flush();
	    } catch( Exception $e ) {
		    if( $this->environment == 'dev' ) {
		    	throw new Exception('Entity Manager Error: '. $e->getMessage());
		    }
			throw new Exception( 'Unknown EM error: '. $e->getCode() .' - '. $e->getMessage() );
	    }
		return $entity;
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
		$test_SubProject = $this->testHelperGet();
		if( !is_null( $test_SubProject ) ) {
			$this->em->remove( $test_SubProject );
	        $this->em->flush();
		}
		$test_SubProject_2 = $this->repo->findOneByName('TestDelprosjekt2');
		if( !is_null( $test_SubProject_2 ) ) {
			$this->em->remove( $test_SubProject_2 );
			$this->em->flush();
		}
    }
    
    /**
	 * Test helper to fetch testSubProject object
	 *
	 * @return SubProject
	*/
    public function testHelperGet() {
   		return $this->repo->findOneByName( 'TestDelprosjekt' );
    }
}