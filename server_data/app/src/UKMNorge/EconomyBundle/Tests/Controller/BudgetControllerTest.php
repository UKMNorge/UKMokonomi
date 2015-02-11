<?php

namespace UKMNorge\EconomyBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Exception;

class BudgetServiceTest extends KernelAwareTest
{
	
	public function testReset() {
		$budgetServ = $this->container->get('UKMeco.budget');
		$budgetServ->testHelperReset();
	}
	
    public function testBudgetCreate() { 
        $budgetServ = $this->container->get('UKMeco.budget');
    
		$userRepo = $this->container->get('doctrine')->getRepository('MariusMandalUserBundle:User');
		$user = $userRepo->findOneById(1);
    
        try {
	        $budget = $budgetServ->create('TestBudsjett', $user);
        } catch (Exception $e ) {
	        throw new Exception('Unable to create test budget. All other tests will fail. Service said: '. $e->getMessage() .' ('. $e->getCode() .')');
        }
        	    
	    $this->assertTrue( get_class( $budget ) == 'UKMNorge\EconomyBundle\Entity\Budget' );
    }

    public function testBudgetUpdateDescription() {
		$budgetServ = $this->container->get('UKMeco.budget');

	    $budget = $budgetServ->testHelperGet();
	    
	    $budgetServ->setDescription( $budget, 'En beskrivelse av test-budsjettet' );
	    
	    $this->assertTrue( $budget->getDescription() == 'En beskrivelse av test-budsjettet' );
	}
	    
    public function testBudgetUpdateName() {
		$budgetServ = $this->container->get('UKMeco.budget');

	    $budget = $budgetServ->testHelperGet();
	    
	    $budgetServ->setName( $budget, 'Testbudsjett2' );
	    
	    $this->assertTrue( $budget->getName() == 'Testbudsjett2' );
	}    
		
	public function testGet() {
		$budgetServ = $this->container->get('UKMeco.budget');
		
		$budgets = $budgetServ->getAll();
		
		$this->assertTrue( is_array( $budgets ) );
	}
}