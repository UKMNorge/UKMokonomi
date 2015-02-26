<?php

namespace UKMNorge\EconomyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use stdClass;

class ProjectController extends Controller
{
	
    public function listAction( $budget )
    {
	    $budgetServ = $this->get('UKMeco.budget');
		$projectServ = $this->get('UKMeco.project');
		$amountServ = $this->get('UKMeco.amount');
		$transactionServ = $this->get('UKMeco.transaction');

		$budget = $budgetServ->get( $budget );

	    $data = array();
	    $data['budget'] = $budget;
	    $data['projects'] = $projectServ->getAll( $budget );
	    $data['transactionServ'] = $transactionServ;
	    $data['transactionTotal'] = $amountServ->getTransactionTotalByBudget( $budget, (int)date("Y") );
        return $this->render('UKMecoBundle:project:index.html.twig', $data);
    }
    
    public function createAction( $budget ) {
	    $budgetServ = $this->get('UKMeco.budget');
		$budget = $budgetServ->get( $budget );

		$userManager = $this->get('fos_user.user_manager');
	    $users = $userManager->findUsers();

	    $yearspan = $this->_yearspan();

	    $data = array();
	    $data['project'] = false;
	    $data['budget'] = $budget;
	    $data['owners'] = $users;
	    $data['yearspan'] = $yearspan;
	    
		return $this->render('UKMecoBundle:project:form.html.twig', $data);
    }
    
    public function doCreateAction( Request $request ) {
		$projectServ = $this->get('UKMeco.project');

		$name		 = $request->request->get('name');
		$description = $request->request->get('description');
		$owner 		 = $request->request->get('owner');
		$budget		 = $request->request->get('budget_id');
		
	    $budgetServ = $this->get('UKMeco.budget');
		$budget = $budgetServ->get( $budget );

   		$em = $this->get('doctrine')->getRepository('MariusMandalUserBundle:User');
		$owner = $em->findOneById( $owner );

	    try {
		    $project = $projectServ->create( $name, $owner, $budget, $description ); 
	    } catch( Exception $e ) {
			return $this->render('UKMecoBundle:project:error.html.twig', array('error' => $e->getCode(), 'name' => $name) );		    
	    }
	    
	    $this->_setAllocatedAmounts( $projectServ, $project, $request->request );

	    return $this->redirect( $this->get('router')->generate('UKMeco_project_homepage', array('budget' => $budget->getId())) );
    }
    
    public function editAction( $budget, $id ) {
		$projectServ = $this->get('UKMeco.project');
	    $budgetServ = $this->get('UKMeco.budget');
		$budget = $budgetServ->get( $budget );
	    $project = $projectServ->get( $id );

		$userManager = $this->get('fos_user.user_manager');
	    $users = $userManager->findUsers();
	    
	    $yearspan = $this->_yearspan();
	    		
	    $data = array();
	    $data['owners'] = $users;
	    $data['budget'] = $budget;
	    $data['project'] = $project;
	    $data['yearspan'] = $yearspan;	    
	    
		return $this->render('UKMecoBundle:project:form.html.twig', $data);
    }
    
    private function _yearspan() {
   		$yearspan = new stdClass();
		$yearspan->start = (int) date('Y') - 1;
		$yearspan->stop = (int) date('Y') + 4;

	    return $yearspan;
    }
    
    public function doEditAction( Request $request, $id ) {
		$projectServ = $this->get('UKMeco.project');
	    $budgetServ = $this->get('UKMeco.budget');
	    
		$name		 = $request->request->get('name');
		$description = $request->request->get('description');
		$owner 		 = $request->request->get('owner');
		$budget		 = $request->request->get('budget_id');
		
		$budget = $budgetServ->get( $budget );
	    $project = $projectServ->get( $id );

   		$em = $this->get('doctrine')->getRepository('MariusMandalUserBundle:User');

		$owner = $em->findOneById( $owner );

	    try {
		    $project = $projectServ->setData( $project, $name, $owner, $budget, $description ); 
	    } catch( Exception $e ) {
			return $this->render('UKMecoBundle:project:error.html.twig', array('error' => $e->getCode(), 'name' => $name) );		    
	    }
	    
	    $this->_setAllocatedAmounts( $projectServ, $project, $request->request );
	    
	    return $this->redirect( $this->get('router')->generate('UKMeco_project_homepage', array('budget' => $budget->getId())) );
    }
    
    private function _setAllocatedAmounts( $projectServ, $project, $postdata ) {
	    foreach( $postdata as $post_key => $amount ) {
		    if( strpos( $post_key, 'amount_' ) === 0 ) {
			    $amount = (int) $amount;
			    $year = (int) str_replace('amount_', '', $post_key);
				$projectServ->setAllocatedAmount( $project, $year, $amount );    
			}
	    }	
	}

}
