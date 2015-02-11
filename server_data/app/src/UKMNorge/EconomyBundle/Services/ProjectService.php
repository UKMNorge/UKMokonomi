<?php
namespace UKMNorge\EconomyBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use MariusMandal\UserBundle\Entity\User;
use UKMNorge\EconomyBundle\Entity\Project;
use Exception;
use DateTime;

class ProjectService
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
     * Get all projects
	 *
     * @return array ?
    */
   	public function get( $id ) {
		$project = $this->repo->findOneById( $id );
		$this->_loadOwnerObject( $project );
		return $project;
   	}
    /**
     * Get all projects
	 *
     * @return array ?
    */
   	public function getAll( $budget ) {
	   	$this->_validateBudget( $budget );
		$projects = $this->repo->findBy( array('budget'=>$budget->getId()), array('name' => 'ASC') );
		$this->_loadOwnerObjects( $projects );
		return $projects;
   	}
    
    /** **************************************************************************************** **/
	/** CREATE FUNCTIONS
    /** **************************************************************************************** **/
    /**
	 * Create project
	 *
	 * @param string $name
	 * @param User $owner
	 *
	 * @return project
	*/
    public function create( $name, $owner, $budget, $description='' ) {
		if( empty( $name ) ) {
			throw new Exception('Project name cannot be empty!', 2000);
		}
		
		$this->_validateOwner( $owner );
	   	$this->_validateBudget( $budget );
		
		// Existing name
		$existing = $this->repo->findOneByName( array('name' => $name, 'budget' => $budget->getId() ) );
		if( !is_null( $existing ) ) {
			throw new Exception('Project name already registered within budget ("'. $budget->getName().'")!', 2001);
		}
		
		$project = new project();
		$project->setName( $name );
		$project->setOwner( $owner->getId() );
		$project->setBudget( $budget->getId() );
		$project->setDescription( $description );
		$this->_persistAndFlush( $project );

		return $project;
    }
    
    /** **************************************************************************************** **/
	/** SET FUNCTIONS
    /** **************************************************************************************** **/

    /**
	 * Set project data
	 *
	 * @param project
	 * @param string Name
	 * @param User $owner
	 * @param string Description
	 * @return project
	*/
	public function setData( $project, $name, $owner, $budget, $description ) {
		$this->setName( $project, $name );
		$this->setOwner( $project, $owner );
		$this->setBudget( $project, $budget );
		$this->setDescription( $project, $description );
		return $project;
	}
    /**
	 * Set project name
	 *
	 * @param project
	 * @param string Name
	 *
	 * @return project
	*/
    public function setName( $project, $name ) {
	    if( empty( $name ) ) {
			throw new Exception('Project name cannot be empty!', 2000);
	    }
	    
	    $this->_validate( $project );
	    
	    $project->setName( $name );
	    $this->_persistAndFlush( $project );
	    return $project;
    }

    /**
	 * Set project description
	 *
	 * @param project
	 * @param string Description
	 *
	 * @return project
	*/
    public function setDescription( $project, $description ) {
	    $this->_validate( $project );	    
		$project->setDescription( $description );
	    $this->_persistAndFlush( $project );
	    return $project;
    } 
    
    /**
	 * Set project owner
	 *
	 * @param project
	 * @param User $owner
	 *
	 * @return project
	*/
    public function setOwner( $project, $owner ) {
	    $this->_validate( $project );
		if( get_class( $owner ) != 'MariusMandal\UserBundle\Entity\User' ) {
			throw new Exception('Owner must be user object! Given '. ( is_object( $owner ) ? get_class( $owner ) : 'invalid object' ), 2002);
		}
		$project->setOwner( $owner->getId() );
	    $this->_persistAndFlush( $project );
	    return $project;
    }

    /**
	 * Set project budget
	 *
	 * @param project
	 * @param Budget $budget
	 *
	 * @return Project
	*/
    public function setBudget( $project, $budget ) {
	    $this->_validate( $project );
	    $this->_validateBudget( $budget );

		$project->setBudget( $budget->getId() );
	    $this->_persistAndFlush( $project );
	    return $project;
    }

    /** **************************************************************************************** **/
	/** LOADERS */
    /** **************************************************************************************** **/

    /**
	 * Loop given projects and load owner objects
	 *
	 * @return void
	*/
	private function _loadOwnerObjects( $projects ) {
		foreach( $projects as $project ) {
			$this->_loadOwnerObject( $project );
		}
	}

    /**
	 * Load owner (public) data into project object
	 *
	 * @return void
	*/	
	private function _loadOwnerObject( $project ) {
		if( !$this->userRepo ) {
			$this->userRepo = $this->container->get('doctrine')->getRepository('MariusMandalUserBundle:User');
		}

		$user = $this->userRepo->findOneById( $project->getOwner() )->getPublicUser();
		$project->setOwnerObject( $user );
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
		$this->repo	 	= $this->doctrine->getRepository('UKMecoBundle:Project');
    }
    
   	private function _validateOwner( $owner ) {
		if( !is_object( $owner ) || get_class( $owner ) != 'MariusMandal\UserBundle\Entity\User' ) {
			throw new Exception('Owner must be user object! Given '. ( is_object( $owner ) ? get_class( $owner ) : 'invalid object' ), 2002);
		}
		return true;
   	}

   	private function _validateBudget( $budget ) {
		if( !is_object( $budget ) || get_class( $budget ) != 'UKMNorge\EconomyBundle\Entity\Budget' ) {
			throw new Exception('Budget must be budget object! Given '. ( is_object( $budget ) ? get_class( $budget ) : 'invalid object' ), 2002);
		}
		return true;
   	}
    
	private function _validate( $project ) {
		if( get_class( $project ) != 'UKMNorge\EconomyBundle\Entity\Project') {
			throw new Exception('Invalid project object!');
		}
	}
    /**
	 * Private helper: Persist project
	 *
	 * @param project
	 *
	 * @return project
	*/

    private function _persistAndFlush( $project ) {
   		$this->em->persist( $project );
        try {
	        $this->em->flush();
	    } catch( Exception $e ) {
		    throw new Exception( 'Unknown EM error: '. $e->getCode() .' - '. $e->getMessage() );
	    }
		return $project;
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
		$test_project = $this->testHelperGet();
		if( !is_null( $test_project ) ) {
			$this->em->remove( $test_project );
	        $this->em->flush();
		}
		$test_project_2 = $this->repo->findOneByName('TestProsjekt2');
		if( !is_null( $test_project_2 ) ) {
			$this->em->remove( $test_project_2 );
			$this->em->flush();
		}
    }
    
    /**
	 * Test helper to fetch testproject object
	 *
	 * @return project
	*/
    public function testHelperGet() {
   		return $this->repo->findOneByName( 'TestProsjekt' );
    }
}