<?php

namespace EvenementBundle\Controller;

use EvenementBundle\Entity\Evenement;
use EvenementBundle\Entity\Participation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UserBundle\Entity\User;

class ParticipationController extends Controller
{
    public function afficherEvenementAction()
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em1=$this->getDoctrine()->getManager();
        $commandes = $em1->getRepository('ShopBundle:Commande')->findBy(array('idClient'=>$user));
        $em=$this->getDoctrine();
        $ev=$this->getDoctrine();
        $tab=$em->getRepository(Evenement::class)->findAll();
        $tab1=$em->getRepository(Participation::class)->findAll();
        return $this->render('@Evenement/Participation/afficherliste.html.twig',array('formations'=>$tab,'formation'=>$tab1,'commandes'=>$commandes));
    }
    public function inscrireEventAction($idu,$id,$email,$username)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em1=$this->getDoctrine()->getManager();
        $commandes = $em1->getRepository('ShopBundle:Commande')->findBy(array('idClient'=>$user));
        $em = $this->getDoctrine()->getManager();
        $event=$em->getRepository('EvenementBundle:Evenement')->find($id);
        //$event->setIdUser($user);
        $tab2=$em->getRepository(Participation::class)->findAllWithCalculus(2);
        if( $event->getNbrParticipant()> 0 && empty($tab2)) {
            $event->setNbrParticipant($event->getNbrParticipant() - 1);

            //decrementer
            $em = $this->getDoctrine()->getManager();

            $ev = $this->getDoctrine()->getManager();

            $tab = $em->getRepository(Evenement::class)->find($id);


            $tab1 = $ev->getRepository(User::class)->find($idu);


            $usr = $this->getDoctrine();

            $participation = new Participation();
            $participation->setEvenementId($tab);
            $participation->setUserId($tab1);
            $participation->setMaileParticipant($email);
            $participation->setDateParticipation(new \DateTime('now'));
            $participation->setRole('admin');

            $em->persist($participation);
            $em->flush();
        }


        $ep=$this->getDoctrine();
        $tab2=$ep->getRepository(Participation::class)->findAll();


        return $this->render('@Evenement/Participation/affichermesevenement.html.twig',array('formations'=>$tab2,'commandes'=>$commandes));


    }

    public function detailAction($id)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em1=$this->getDoctrine()->getManager();
        $commandes = $em1->getRepository('ShopBundle:Commande')->findBy(array('idClient'=>$user));
        $em=$this->getDoctrine();
        $tab=$em->getRepository(Evenement::class)->findByid($id);
        return $this->render('@Evenement/Participation/detail.html.twig',array('formations'=>$tab,'commandes'=>$commandes));
    }

    public function affichparticipationAction()
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em1=$this->getDoctrine()->getManager();
        $commandes = $em1->getRepository('ShopBundle:Commande')->findBy(array('idClient'=>$user));
        $em=$this->getDoctrine();
        $tab=$em->getRepository(Participation::class)->findAll();
        return $this->render('@Evenement/Participation/affichermesevenement.html.twig',array('formations'=>$tab,'commandes'=>$commandes));
    }
    public function liveAction()
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em1=$this->getDoctrine()->getManager();
        $commandes = $em1->getRepository('ShopBundle:Commande')->findBy(array('idClient'=>$user));
        return $this->render('@Evenement/Participation/live.html.twig',array('commandes'=>$commandes));

    }
}
