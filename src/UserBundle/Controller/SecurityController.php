<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityController extends Controller
{
    public function addAction()
    {
        return $this->render('@User/Security/user_home.html.twig', array(
            // ...
        ));
    }

    public function redirectAction()
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em1=$this->getDoctrine()->getManager();
        $commandes = $em1->getRepository('ShopBundle:Commande')->findBy(array('idClient'=>$user));
        $authChecker=$this->container->get('security.authorization_checker');
        if($authChecker->isGranted('ROLE_ADMIN')){
            return $this->render('@User/Security/admin_home.html.twig', array(
                'commandes'=>$commandes
            ));
        }else if ($authChecker->isGranted('ROLE_USER')) {
            return $this->render('@User/Security/user_home.html.twig', array('commandes'=>$commandes
            ));
        }
        else {
            return $this->render('@FOSUser/Security/login.html.twig', array('commandes'=>$commandes
            ));
        }
    }

}
