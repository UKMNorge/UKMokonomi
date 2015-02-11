<?php

namespace UKMNorge\EconomyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BudgetController extends Controller
{
	
    public function listAction()
    {
		$budgetServ = $this->get('UKMeco.budget');

	    $data = array();
	    $data['budgets'] = $budgetServ->getAll();
        return $this->render('UKMecoBundle:Budget:index.html.twig', $data);
    }
    
    public function createAction() {
	    $data = array();
	    $data['budget'] = false;


		$userManager = $this->get('fos_user.user_manager');
	    $users = $userManager->findUsers();
	    $data['owners'] = $users;
	    
		return $this->render('UKMecoBundle:Budget:form.html.twig', $data);
    }
    
    public function doCreateAction( Request $request ) {
		$budgetServ	= $this->get('UKMeco.budget');

		$name		 = $request->request->get('name');
		$description = $request->request->get('description');
		$owner 		 = $request->request->get('owner');


   		$em = $this->get('doctrine')->getRepository('MariusMandalUserBundle:User');

		$owner = $em->findOneById( $owner );

	    try {
		    $budget = $budgetServ->create( $name, $owner, $description ); 
	    } catch( Exception $e ) {
			return $this->render('UKMecoBundle:Budget:error.html.twig', array('error' => $e->getCode(), 'name' => $name) );		    
	    }
	    
	    return $this->redirect( $this->get('router')->generate('UKMeco_budget_homepage') );
    }
    
    public function editAction( $id ) {
	    $budgetServ = $this->get('UKMeco.budget');
	    
	    $data = array();
	    $data['budget'] = $budgetServ->get( $id );


		$userManager = $this->get('fos_user.user_manager');
	    $users = $userManager->findUsers();
	    $data['owners'] = $users;
	    
		return $this->render('UKMecoBundle:Budget:form.html.twig', $data);
    }
    
    public function doEditAction( Request $request, $id ) {
		$budgetServ	= $this->get('UKMeco.budget');

		$name		 = $request->request->get('name');
		$description = $request->request->get('description');
		$owner 		 = $request->request->get('owner');

	    $budget = $budgetServ->get( $id );

   		$em = $this->get('doctrine')->getRepository('MariusMandalUserBundle:User');

		$owner = $em->findOneById( $owner );

	    try {
		    $budget = $budgetServ->setData( $budget, $name, $owner, $description ); 
	    } catch( Exception $e ) {
			return $this->render('UKMecoBundle:Budget:error.html.twig', array('error' => $e->getCode(), 'name' => $name) );		    
	    }
	    
	    return $this->redirect( $this->get('router')->generate('UKMeco_budget_homepage') );
    }

}
