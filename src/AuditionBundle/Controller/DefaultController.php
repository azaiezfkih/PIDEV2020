<?php

namespace AuditionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AuditionBundle:Default:index.html.twig');
    }
}
