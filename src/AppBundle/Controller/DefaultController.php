<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em1=$this->getDoctrine()->getManager();
        $commandes = $em1->getRepository('ShopBundle:Commande')->findBy(array('idClient'=>$user));
        return $this->render('base.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,'commandes'=>$commandes
        ]);
    }
}
