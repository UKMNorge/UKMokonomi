<?php

namespace UKMNorge\EconomyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Exception;

class TransactionController extends Controller
{
	
    public function listAction( $budget, $project, $subproject )
    {
	    $budgetServ = $this->get('UKMeco.budget');
	    $projectServ = $this->get('UKMeco.project');
		$subProjectServ = $this->get('UKMeco.subproject');
		$transactionServ = $this->get('UKMeco.transaction');

		$budget = $budgetServ->get( $budget );
		$project = $projectServ->get( $project );
		$subProject = $subProjectServ->get( $subproject );

	    $data = array();
	    $data['budget'] = $budget;
	    $data['project'] = $project;
	    $data['subproject'] = $subProject;
	    $data['transactionServ'] = $transactionServ;
	    $data['transactions'] = $transactionServ->getAll( $subProject );
	    
        return $this->render('UKMecoBundle:Transaction:index.html.twig', $data);
    }
    
    public function createAction( $budget, $project, $subproject ) {
	    $budgetServ = $this->get('UKMeco.budget');
	    $projectServ = $this->get('UKMeco.project');
		$subProjectServ = $this->get('UKMeco.subproject');

		$budget = $budgetServ->get( $budget );
		$project = $projectServ->get( $project );
		$subproject = $subProjectServ->get( $subproject );

	    $data = array();
	    $data['budget'] = $budget;
	    $data['project'] = $project;
	    $data['subproject'] = $subproject;
	    $data['transaction'] = false;
	    
		return $this->render('UKMecoBundle:Transaction:form.html.twig', $data);
    }
    
    public function doCreateAction( Request $request ) {
	    $budgetServ = $this->get('UKMeco.budget');
	    $projectServ = $this->get('UKMeco.project');
		$subProjectServ = $this->get('UKMeco.subproject');
		$transactionServ = $this->get('UKMeco.transaction');

		$name		 = $request->request->get('name');
		$type		 = $request->request->get('type');
		$amount		 = $request->request->get('amount');
		$description = $request->request->get('description');
		$bilag		 = $request->request->get('bilag');
		$subproject	 = $request->request->get('subproject_id');
		$project	 = $request->request->get('project_id');
		$budget		 = $request->request->get('budget_id');
		
		$budget = $budgetServ->get( $budget );
		$project = $projectServ->get( $project );
		$subproject = $subProjectServ->get( $subproject );

	    try {
		    $transaction = $transactionServ->create( $name, $type, $amount, $subproject, $description, $bilag ); 
	    } catch( Exception $e ) {
			return $this->render('UKMecoBundle:Transaction:error.html.twig', array('error' => $e->getCode(), 'name' => $name) );		    
	    }
	    
	    return $this->redirect( $this->get('router')->generate('UKMeco_transaction_homepage', array('subproject' => $subproject->getId(), 'project' => $project->getId(), 'budget' => $budget->getId())) );
    }
    
    public function editAction( $project, $budget, $subproject, $id ) {
	    $budgetServ = $this->get('UKMeco.budget');
	    $projectServ = $this->get('UKMeco.project');
		$subProjectServ = $this->get('UKMeco.subproject');
		$transactionServ = $this->get('UKMeco.transaction');

		$budget = $budgetServ->get( $budget );
		$project = $projectServ->get( $project );
		$subproject = $subProjectServ->get( $subproject );

	    $data = array();
	    $data['budget'] = $budget;
	    $data['project'] = $project;
	    $data['subproject'] = $subproject;
	    $data['transaction'] = $transactionServ->get( $id );
	    
		return $this->render('UKMecoBundle:Transaction:form.html.twig', $data);
    }
    
    public function doDeleteAction( Request $request, $budget, $project, $subproject, $id ) {
		$session = new Session();
		
	    $transactionServ = $this->get('UKMeco.transaction');
		$transaction = $transactionServ->get( $id );
		try {
		    $transactionServ->destroy( $transaction );
			$session->getFlashBag()->set('success', 'Transaksjon slettet!');
		} catch( Exception $e ) {
			$session->getFlashBag()->set('danger', 'Transaksjonen ble ikke slettet pga følgende: '. $e->getMessage() );
		}

	    return $this->redirect( $this->get('router')->generate('UKMeco_transaction_homepage', 
	    														array('subproject' => $subproject, 
																	  'project' => $project, 
																	  'budget' => $budget) 
																	 )
															   );
    }
    
    public function doEditAction( Request $request, $id ) {
	    $budgetServ = $this->get('UKMeco.budget');
	    $projectServ = $this->get('UKMeco.project');
		$subProjectServ = $this->get('UKMeco.subproject');
		$transactionServ = $this->get('UKMeco.transaction');

		$name		 = $request->request->get('name');
		$type		 = $request->request->get('type');
		$amount		 = $request->request->get('amount');
		$description = $request->request->get('description');
		$subproject	 = $request->request->get('subproject_id');
		$project	 = $request->request->get('project_id');
		$budget		 = $request->request->get('budget_id');
		$bilag		 = $request->request->get('bilag');
		
		$budget = $budgetServ->get( $budget );
		$project = $projectServ->get( $project );
		$subproject = $subProjectServ->get( $subproject );
		$transaction = $transactionServ->get( $id );
	    try {
		    $transaction = $transactionServ->setData( $transaction, $name, $type, $amount, $subproject, $description, $bilag ); 
	    } catch( Exception $e ) {
			return $this->render('UKMecoBundle:Transaction:error.html.twig', array('error' => $e->getCode(), 'name' => $name) );		    
	    }
	    
	    return $this->redirect( $this->get('router')->generate('UKMeco_transaction_homepage', array('subproject' => $subproject->getId(), 'project' => $project->getId(), 'budget' => $budget->getId())) );

    }

}
