<?php

namespace UKMNorge\EconomyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ReportController extends Controller
{
    public function indexAction()
    {
		return $this->render('UKMecoBundle:Report:index.html.twig', array());
    }
    
    public function yearAction( $year ) {
   		$budgetServ = $this->get('UKMeco.budget');
		$transactionServ = $this->get('UKMeco.transaction');
	    $data = array();    
	    $data['budgetGroups'] = $budgetServ->getGroups( $transactionServ, $year );
	    $data['transactionServ'] = $transactionServ; 
	    $data['projectServ'] = $this->get('UKMeco.project');
	    $data['amountServ'] = $this->get('UKMeco.amount');
	    $data['user'] = $this->get('security.context')->getToken()->getUser();
	    $data['year'] = $year;

		return $this->render('UKMecoBundle:Report:year.html.twig', $data );
    }
}