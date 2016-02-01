<?php

namespace AMAPBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AMAPBundle:Default:index.html.twig');
    }
    
    public function produitAction()
    {
        return $this->render('AMAPBundle:Default:index.html.twig');
    }
}
