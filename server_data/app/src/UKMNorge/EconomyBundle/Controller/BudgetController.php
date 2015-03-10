<?php

namespace UKMNorge\EconomyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use stdClass;

class BudgetController extends Controller
{
	
    public function listAction()
    {
		$budgetServ = $this->get('UKMeco.budget');
		$transactionServ = $this->get('UKMeco.transaction');

	    $data = array();
	    $budgetsArray = $budgetServ->getAll();
	    
	    $groups = array();
	    
	    foreach( $budgetsArray as $budget ) {
		    $code = $budget->getCode();
		    if( empty( $code ) ) {
			    $code = 'Ukjent';
		    } else {
			    $code = substr( (string)$code, 0, 2);
		    }
		    
		    if( !isset( $groups[ $code ] ) ) {
			    $groups[ $code ] = new stdClass();
			    $groups[ $code ]->budgets = array();
			    $groups[ $code ]->name = $code;
			    $groups[ $code ]->sum = new stdClass();
			    $groups[ $code ]->sum->allocated = 0;
			    $groups[ $code ]->sum->subProjects = 0;
			    $groups[ $code ]->sum->transactions = 0;
		    }
			
			$groups[ $code ]->budgets[ $budget->getName() ] = $budget;
			$groups[ $code ]->sum->allocated	+= $budget->getAllocatedAmount( date("Y") );
			$groups[ $code ]->sum->subProjects	+= $budget->getSubProjectsAllocatedTotal( date("Y") );
			$groups[ $code ]->sum->transactions	+= $transactionServ->getTotalByBudget( $budget, date("Y") );
			ksort( $groups[ $code ]->budgets );
		}
		ksort( $groups );

	    $data['budgetGroups'] = $groups;
	    $data['transactionServ'] = $transactionServ; 
	    $data['amountServ'] = $this->get('UKMeco.amount');
	    $data['user'] = $this->get('security.context')->getToken()->getUser();
        return $this->render('UKMecoBundle:Budget:index.html.twig', $data);
    }
    
    public function createAction() {
		$userManager = $this->get('fos_user.user_manager');
	    $users = $userManager->findUsers();
   	    $yearspan = $this->_yearspan();

	    $data = array();
	    $data['budget'] = false;
	    $data['owners'] = $users;
	    $data['yearspan'] = $yearspan;
	    
		return $this->render('UKMecoBundle:Budget:form.html.twig', $data);
    }
    
    public function doCreateAction( Request $request ) {
		$budgetServ	= $this->get('UKMeco.budget');

		$name		 = $request->request->get('name');
		$description = $request->request->get('description');
		$owner 		 = $request->request->get('owner');
		$code 		 = $request->request->get('code');


   		$em = $this->get('doctrine')->getRepository('MariusMandalUserBundle:User');

		$owner = $em->findOneById( $owner );

	    try {
		    $budget = $budgetServ->create( $name, $owner, $description, $code); 
	    } catch( Exception $e ) {
			return $this->render('UKMecoBundle:Budget:error.html.twig', array('error' => $e->getCode(), 'name' => $name) );		    
	    }
	    
	    $this->_setAllocatedAmounts( $budgetServ, $budget, $request->request );
	    
	    return $this->redirect( $this->get('router')->generate('UKMeco_budget_homepage') );
    }
    
    public function editAction( $id ) {
	    $budgetServ = $this->get('UKMeco.budget');
   	    $yearspan = $this->_yearspan();
	    
		$userManager = $this->get('fos_user.user_manager');
	    $users = $userManager->findUsers();

	    $data = array();
	    $data['budget'] = $budgetServ->get( $id );
	    $data['yearspan'] = $yearspan;
	    $data['owners'] = $users;
	    
		return $this->render('UKMecoBundle:Budget:form.html.twig', $data);
    }
    
    public function doEditAction( Request $request, $id ) {
		$budgetServ	= $this->get('UKMeco.budget');

		$name		 = $request->request->get('name');
		$description = $request->request->get('description');
		$owner 		 = $request->request->get('owner');
		$code 		 = $request->request->get('code');

	    $budget = $budgetServ->get( $id );

   		$em = $this->get('doctrine')->getRepository('MariusMandalUserBundle:User');

		$owner = $em->findOneById( $owner );

	    try {
		    $budget = $budgetServ->setData( $budget, $name, $owner, $description, $code ); 
	    } catch( Exception $e ) {
			return $this->render('UKMecoBundle:Budget:error.html.twig', array('error' => $e->getCode(), 'name' => $name) );		    
	    }
	    
	    $this->_setAllocatedAmounts( $budgetServ, $budget, $request->request );
	    
	    return $this->redirect( $this->get('router')->generate('UKMeco_budget_homepage') );
    }
    
    private function _setAllocatedAmounts( $budgetServ, $budget, $postdata ) {
	    foreach( $postdata as $post_key => $amount ) {
		    if( strpos( $post_key, 'amount_' ) === 0 ) {
			    $amount = (int) $amount;
			    $year = (int) str_replace('amount_', '', $post_key);
				$budgetServ->setAllocatedAmount( $budget, $year, $amount );    
			}
	    }	
	}
    
    private function _yearspan() {
   		$yearspan = new stdClass();
		$yearspan->start = (int) date('Y') - 1;
		$yearspan->stop = (int) date('Y') + 4;

	    return $yearspan;
    }
}
