<?php

namespace UKMNorge\EconomyBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Exception;

class projectServiceTest extends KernelAwareTest
{
	
	public function testReset() {
		$projectServ = $this->container->get('UKMeco.project');
		$projectServ->testHelperReset();
	}
	
    public function testProjectCreate() { 
        $projectServ = $this->container->get('UKMeco.project');
    
		$userRepo = $this->container->get('doctrine')->getRepository('MariusMandalUserBundle:User');
		$user = $userRepo->findOneById(1);
		
		$budget = $this->_getBudget();
		
        try {
	        $project = $projectServ->create('TestProsjekt', $user, $budget);
        } catch (Exception $e ) {
	        throw new Exception('Unable to create test project. All other tests will fail. Service said: '. $e->getMessage() .' ('. $e->getCode() .')');
        }
        	    
	    $this->assertTrue( get_class( $project ) == 'UKMNorge\EconomyBundle\Entity\Project' );
    }

    public function testProjectUpdateDescription() {
		$projectServ = $this->container->get('UKMeco.project');

	    $project = $projectServ->testHelperGet();
	    
	    $projectServ->setDescription( $project, 'En beskrivelse av test-prosjektet' );
	    
	    $this->assertTrue( $project->getDescription() == 'En beskrivelse av test-prosjektet' );
	}
	    
    public function testProjectUpdateName() {
		$projectServ = $this->container->get('UKMeco.project');

	    $project = $projectServ->testHelperGet();
	    
	    $projectServ->setName( $project, 'TestProsjekt2' );
	    
	    $this->assertTrue( $project->getName() == 'TestProsjekt2' );
	}    
		
	public function testGet() {
		$budget = $this->_getBudget();

		$projectServ = $this->container->get('UKMeco.project');
		$projects = $projectServ->getAll( $budget );
		
		$this->assertTrue( is_array( $projects ) );
	}
	
	private function _getBudget() {
		$budgetRepo = $this->container->get('doctrine')->getRepository('UKMecoBundle:Budget');
		$budget = $budgetRepo->findOneByName('Testbudsjett');
		if( !$budget ) {
			$budget = $budgetRepo->findOneByName('Testbudsjett2');
		}
		return $budget;
	}
}