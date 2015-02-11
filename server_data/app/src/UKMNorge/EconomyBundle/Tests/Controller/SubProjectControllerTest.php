<?php

namespace UKMNorge\EconomyBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Exception;

class SubProjectServiceTest extends KernelAwareTest
{
	
	public function testReset() {
		$SubProjectServ = $this->container->get('UKMeco.subproject');
		$SubProjectServ->testHelperReset();
	}
	

    public function testSubProjectCreate() { 
        $SubProjectServ = $this->container->get('UKMeco.subproject');
    
		$project = $this->_getProject();		

        try {
	        $SubProject = $SubProjectServ->create('TestDelProsjekt', $project, 'TestBeskrivelse');
        } catch (Exception $e ) {
	        throw new Exception('Unable to create test SubProject. All other tests will fail. Service said: '. $e->getMessage() .' ('. $e->getCode() .')');
        }
        	    
	    $this->assertTrue( get_class( $SubProject ) == 'UKMNorge\EconomyBundle\Entity\SubProject' );
    }

    public function testSubProjectUpdateDescription() {
		$SubProjectServ = $this->container->get('UKMeco.subproject');

	    $SubProject = $SubProjectServ->testHelperGet();
	    
	    $SubProjectServ->setDescription( $SubProject, 'En beskrivelse av test-del-prosjektet' );
	    
	    $this->assertTrue( $SubProject->getDescription() == 'En beskrivelse av test-del-prosjektet' );
	}
	    
    public function testSubProjectUpdateName() {
		$SubProjectServ = $this->container->get('UKMeco.subproject');

	    $SubProject = $SubProjectServ->testHelperGet();
	    
	    $SubProjectServ->setName( $SubProject, 'TestDelProsjekt2' );
	    
	    $this->assertTrue( $SubProject->getName() == 'TestDelProsjekt2' );
	}    
		
	public function testGet() {
		$project = $this->_getProject();
		$SubProjectServ = $this->container->get('UKMeco.subproject');
		$SubProjects = $SubProjectServ->getAll( $project );
		
		$this->assertTrue( is_array( $SubProjects ) );
	}
	
	private function _getProject() {
		$projectRepo = $this->container->get('doctrine')->getRepository('UKMecoBundle:Project');
		$project = $projectRepo->findOneByName('TestProsjekt');
		if( !$project ) {
			$project = $projectRepo->findOneByName('TestProsjekt2');
		}
		return $project;
	}
}