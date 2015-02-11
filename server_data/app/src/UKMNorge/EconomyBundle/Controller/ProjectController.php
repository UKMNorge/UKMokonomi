<?php

namespace UKMNorge\EconomyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProjectController extends Controller
{
	
    public function listAction( $budget )
    {
	    $budgetServ = $this->get('UKMeco.budget');
		$projectServ = $this->get('UKMeco.project');

		$budget = $budgetServ->get( $budget );

	    $data = array();
	    $data['budget'] = $budget;
	    $data['projects'] = $projectServ->getAll( $budget );
        return $this->render('UKMecoBundle:project:index.html.twig', $data);
    }
    
    public function createAction( $budget ) {
	    $budgetServ = $this->get('UKMeco.budget');
		$budget = $budgetServ->get( $budget );

	    $data = array();
	    $data['project'] = false;
	    $data['budget'] = $budget;

		$userManager = $this->get('fos_user.user_manager');
	    $users = $userManager->findUsers();
	    $data['owners'] = $users;
	    
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
	    
	    return $this->redirect( $this->get('router')->generate('UKMeco_project_homepage', array('budget' => $budget->getId())) );
    }
    
    public function editAction( $budget, $id ) {
		$projectServ = $this->get('UKMeco.project');
	    $budgetServ = $this->get('UKMeco.budget');
		$budget = $budgetServ->get( $budget );

	    $data = array();
	    $data['project'] = $projectServ->get( $id );
	    $data['budget'] = $budget;

		$userManager = $this->get('fos_user.user_manager');
	    $users = $userManager->findUsers();
	    $data['owners'] = $users;
	    
		return $this->render('UKMecoBundle:project:form.html.twig', $data);
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
	    
	    return $this->redirect( $this->get('router')->generate('UKMeco_project_homepage', array('budget' => $budget->getId())) );
    }

}
