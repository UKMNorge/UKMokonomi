<?php

namespace UKMNorge\EconomyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use stdClass;
use Exception;

class SubProjectController extends Controller
{
	
    public function listAction( $budget, $project )
    {
	    $budgetServ = $this->get('UKMeco.budget');
	    $projectServ = $this->get('UKMeco.project');
		$subProjectServ = $this->get('UKMeco.subproject');
		$transactionServ = $this->get('UKMeco.transaction');
		$amountServ = $this->get('UKMeco.amount');

		$budget = $budgetServ->get( $budget );
		$project = $projectServ->get( $project );

	    $data = array();
	    $data['budget'] = $budget;
	    $data['project'] = $project;
	    $data['subprojects'] = $subProjectServ->getAll( $project );
	    $data['transactionServ'] = $transactionServ;
	    $data['transactionTotal'] = $amountServ->getTransactionTotalByProject( $project, (int)date("Y") );	    	    
        return $this->render('UKMecoBundle:SubProject:index.html.twig', $data);
    }
    
    public function deleteAction( $project, $budget, $id ) {
	    $budgetServ = $this->get('UKMeco.budget');
	    $projectServ = $this->get('UKMeco.project');
		$subProjectServ = $this->get('UKMeco.subproject');
		$transactionServ = $this->get('UKMeco.transaction');
	    $session = new Session();

		$budget = $budgetServ->get( $budget );
		$project = $projectServ->get( $project );
		$subproject = $subProjectServ->get( $id );

		try {
			$subProjectServ->destroy( $subproject, $transactionServ );
			$session->getFlashBag()->set('success', 'Utgiftsgruppen er slettet!');
		} catch( Exception $e ) {
			$session->getFlashBag()->set('danger', 'Utgiftsgruppen ble ikke slettet pga følgende feilmelding: '. $e->getMessage() );
		}

	    return $this->redirect( $this->get('router')->generate('UKMeco_subproject_homepage', array('project' => $project->getId(), 'budget' => $budget->getId())) );
	}
    
    public function createAction( $budget, $project ) {
	    $budgetServ = $this->get('UKMeco.budget');
	    $projectServ = $this->get('UKMeco.project');
		$subProjectServ = $this->get('UKMeco.subproject');

		$budget = $budgetServ->get( $budget );
		$project = $projectServ->get( $project );
		
		$yearspan = $this->_getYearSpan();

	    $data = array();
	    $data['budget'] = $budget;
	    $data['project'] = $project;
	    $data['subproject'] = false;
	    $data['yearspan'] = $yearspan;
	    
	    $allocatedAmounts = array();
	    for( $i=$yearspan->start; $i<$yearspan->stop+1; $i++) {
		    $allocatedAmounts[ $i ] = 0;
	    }
		$data['allocatedAmounts'] = $allocatedAmounts;
	    
		return $this->render('UKMecoBundle:SubProject:form.html.twig', $data);
    }
    
    public function doCreateAction( Request $request ) {
	    $budgetServ = $this->get('UKMeco.budget');
	    $projectServ = $this->get('UKMeco.project');
		$subProjectServ = $this->get('UKMeco.subproject');

		$name		 = $request->request->get('name');
		$description = $request->request->get('description');
		$project	 = $request->request->get('project_id');
		$budget		 = $request->request->get('budget_id');
		
		$budget = $budgetServ->get( $budget );
		$project = $projectServ->get( $project );

	    try {
		    $SubProject = $subProjectServ->create( $name, $project, $description ); 
	    } catch( Exception $e ) {
			return $this->render('UKMecoBundle:SubProject:error.html.twig', array('error' => $e->getCode(), 'name' => $name) );		    
	    }
	    
	    $this->_setAllocatedAmounts( $subProjectServ, $SubProject, $request->request );
	    
	    return $this->redirect( $this->get('router')->generate('UKMeco_subproject_homepage', array('project' => $project->getId(), 'budget' => $budget->getId())) );
    }
    
    public function editAction( $project, $budget, $id ) {
	    $budgetServ = $this->get('UKMeco.budget');
	    $projectServ = $this->get('UKMeco.project');
		$subProjectServ = $this->get('UKMeco.subproject');

		$budget = $budgetServ->get( $budget );
		$project = $projectServ->get( $project );
		$subproject = $subProjectServ->get( $id );

		$yearspan = $this->_getYearSpan();
		
	    $data = array();
	    $data['budget'] = $budget;
	    $data['project'] = $project;
	    $data['subproject'] = $subproject;
	    $data['yearspan'] = $yearspan;
	    $data['allocatedAmounts'] = $subProjectServ->getAllocatedAmounts( $subproject, $yearspan->start, $yearspan->stop );
	   	    
		return $this->render('UKMecoBundle:SubProject:form.html.twig', $data);
    }
    
    public function doEditAction( Request $request, $id ) {
	    $budgetServ = $this->get('UKMeco.budget');
	    $projectServ = $this->get('UKMeco.project');
		$subProjectServ = $this->get('UKMeco.subproject');

		$name		 = $request->request->get('name');
		$description = $request->request->get('description');
		$project	 = $request->request->get('project_id');
		$budget		 = $request->request->get('budget_id');
		
		$budget = $budgetServ->get( $budget );
		$project = $projectServ->get( $project );
		$subproject = $subProjectServ->get( $id );

		// Update subproject data
	    try {
		    $subProjectServ->setData( $subproject, $name, $project, $description ); 
	    } catch( Exception $e ) {
			return $this->render('UKMecoBundle:SubProject:error.html.twig', array('error' => $e->getCode(), 'name' => $name) );		    
	    }
	    
	    $this->_setAllocatedAmounts( $subProjectServ, $subproject, $request->request );
	    return $this->redirect( $this->get('router')->generate('UKMeco_subproject_homepage', array('project' => $project->getId(), 'budget' => $budget->getId())) );
    }

    private function _setAllocatedAmounts( $subProjectServ, $subproject, $postdata ) {
	    foreach( $postdata as $post_key => $amount ) {
		    if( strpos( $post_key, 'amount_' ) === 0 ) {
			    $amount = (int) $amount;
			    $year = (int) str_replace('amount_', '', $post_key);
				$subProjectServ->setAllocatedAmount( $subproject, $year, $amount );    
			}
	    }	
	}
    
    private function _getYearSpan() {
		$yearspan = new stdClass();
		$yearspan->start = (int) date('Y') - 1;
		$yearspan->stop = (int) date('Y') + 4;
		return $yearspan;
    }
}
