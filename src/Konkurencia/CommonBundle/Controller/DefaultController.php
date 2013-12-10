<?php

namespace Konkurencia\CommonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('KonkurenciaCommonBundle:Default:index.html.twig', array('name' => $name));
    }
}
