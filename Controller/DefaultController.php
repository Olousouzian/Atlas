<?php

namespace Olousouzian\AtlasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('OlousouzianAtlasBundle:Default:index.html.twig');
    }
}
