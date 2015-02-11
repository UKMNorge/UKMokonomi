<?php

namespace UKMNorge\EconomyBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Exception;

class TransactionServiceTest extends KernelAwareTest
{
	
	public function testReset() {
		$transactionServ = $this->container->get('UKMeco.transaction');
		$transactionServ->testHelperReset();
	}
	

    public function testTransactionCreate() { 
        $transactionServ = $this->container->get('UKMeco.transaction');
    		
		$subProject = $this->_getSubProject();	
        try {
	        $transaction = $transactionServ->create('TestTransaksjon', 'Budget', 1000, $subProject, 'TestBeskrivelse');
        } catch (Exception $e ) {
	        throw new Exception('Unable to create test Transaction. All other tests will fail. Service said: '. $e->getMessage() .' ('. $e->getCode() .')');
        }
        	    
	    $this->assertTrue( get_class( $transaction ) == 'UKMNorge\EconomyBundle\Entity\Transaction' );
    }

    public function testTransactionUpdateName() {
		$transactionServ = $this->container->get('UKMeco.transaction');
	    $transaction = $transactionServ->testHelperGet();
	    
	    $transactionServ->setName($transaction, 'TestTransaksjon2');
	    $this->assertTrue( $transaction->getName() == 'TestTransaksjon2' );
	}    

    public function testTransactionUpdateType() {
		$transactionServ = $this->container->get('UKMeco.transaction');
	    $transaction = $transactionServ->testHelperGet();
	    
	    $transactionServ->setType( $transaction, 'Accounting' );
	    $this->assertTrue( $transaction->getType() == 'Accounting' );
	}    

    public function testTransactionUpdateAmount() {
		$transactionServ = $this->container->get('UKMeco.transaction');
	    $transaction = $transactionServ->testHelperGet();
	    
	    $transactionServ->setAmount( $transaction, 2000 );
	    $this->assertTrue( $transaction->getAmount() == 2000 );
	}    

    public function testTransactionUpdateDescription() {
		$transactionServ = $this->container->get('UKMeco.transaction');
	    $transaction = $transactionServ->testHelperGet();
	    
	    $transactionServ->setDescription( $transaction, 'En beskrivelse av test-transaksjon' );	    
	    $this->assertTrue( $transaction->getDescription() == 'En beskrivelse av test-transaksjon' );
	}
	    
		
	public function testGet() {
		$project = $this->_getProject();
		$transactionServ = $this->container->get('UKMeco.transaction');
		$transactions = $transactionServ->getAll( $project );
		
		$this->assertTrue( is_array( $transactions ) );
	}
	
	private function _getSubProject() {
		$subProjectRepo = $this->container->get('doctrine')->getRepository('UKMecoBundle:SubProject');
		$subProject = $subProjectRepo->findOneByName('TestDelProsjekt');
		if( !$subProject ) {
			$subProject = $subProjectRepo->findOneByName('TestDelProsjekt2');
		}
		return $subProject;
	}
	
	private function _getProject() {
		$projectRepo = $this->container->get('doctrine')->getRepository('UKMecoBundle:Project');
		$project = $projectRepo->findOneByName('TestProsjekt');
		if( !$project ) {
			$project = $projectRepo->findOneByName('TestProsjekt2');
		}
		return $project;
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