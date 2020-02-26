<?php

namespace ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TestController extends Controller
{
    public function ajouterAction()
    {
        return $this->render('ShopBundle:Test:ajouter.html.twig', array(
            // ...
        ));
    }

}
