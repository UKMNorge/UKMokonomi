<?php

namespace UKMNorge\EconomyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends Controller
{
	public function searchAction( Request $request )
	{
		$viewData = array('budgets'=>array());
		$budgetServ = $this->get('UKMeco.budget');
		$projectServ = $this->get('UKMeco.project');
		$subProjectServ = $this->get('UKMeco.subproject');
		$transactionServ = $this->get('UKMeco.transaction');

		$searchfor = $request->request->get('search');

		$viewData['searchfor'] = $searchfor;
		$viewData['budgetServ'] = $budgetServ;
		$viewData['projectServ'] = $projectServ;
	
		$viewData['budgets'] = $budgetServ->search( $searchfor );
		$viewData['projects'] = $projectServ->search( $searchfor );
		$viewData['subprojects'] = $subProjectServ->search( $searchfor );
		$viewData['transactions'] = $transactionServ->search( $searchfor );
		
		return $this->render('UKMecoBundle:Search:results.html.twig', $viewData);
	}
}
