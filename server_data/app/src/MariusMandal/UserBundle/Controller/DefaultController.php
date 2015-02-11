<?php

namespace MariusMandal\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('MariusMandalUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
