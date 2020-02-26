<?php

namespace PublicationBundle\Controller;

use PublicationBundle\Entity\Publication;
use PublicationBundle\Entity\Vote;
use PublicationBundle\Form\PublicationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UserBundle\Entity\User;

class PublicationController extends Controller
{
    public function ListePublicationAction ()
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em1=$this->getDoctrine()->getManager();
        $commandes = $em1->getRepository('ShopBundle:Commande')->findBy(array('idClient'=>$user));
        $user=$this->getUser();

        $em = $this->getDoctrine()->getManager();
        $publication= $em->getRepository("PublicationBundle:Publication")->findAll();
        $vote=$em->getRepository(Vote::class)->findAll();

        return $this->render('@Publication/front/listePublication.html.twig',
            array(
                'publications' => $publication,
                'vote'=>$vote,
                'user'=>$user,'commandes'=>$commandes

            ));
    }
    public function AfficherPublicationAction ()
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em1=$this->getDoctrine()->getManager();
        $commandes = $em1->getRepository('ShopBundle:Commande')->findBy(array('idClient'=>$user));
        $em = $this->getDoctrine()->getManager();
        $publication= $em->getRepository("PublicationBundle:Publication")->findAll();
        return $this->render('@Publication/back/AfficherPublication.html.twig', array('publications' => $publication,'commandes'=>$commandes));
    }

    public function ajouterAction(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em1=$this->getDoctrine()->getManager();
        $commandes = $em1->getRepository('ShopBundle:Commande')->findBy(array('idClient'=>$user));
        $user=$this->getUser();
        $publication=new Publication();
        $form=$this->createForm(PublicationType::class,$publication);
        $form->handleRequest($request);
        $date_current=new \DateTime();
        $publication->setDate($date_current);
        $publication->setIdUser($this->getUser());

        if($form->isSubmitted())
        {
            if ($form->isSubmitted() && $form->isValid()) {
                $file = $publication->getPhoto();
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($this->getParameter('photos_directory'), $fileName);
                $publication->setPhoto($fileName);

            }
            $em=$this->getDoctrine()->getManager();
            $em->persist($publication);
            $em->flush();
            return $this->redirectToRoute('publication');
        };
        return $this->render("@Publication/front/AjouterPublication.html.twig",array('form'=>$form->createView(),'commandes'=>$commandes));

    }


    function UpdatePublicationAction($id,Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em1=$this->getDoctrine()->getManager();
        $commandes = $em1->getRepository('ShopBundle:Commande')->findBy(array('idClient'=>$user));
        $em=$this->getDoctrine()->getManager();
        $publication=$em->getRepository(Publication::class)->find($id);
        $Form=$this->createForm(PublicationType::class,$publication);
        $Form->handleRequest($request);
        $date=new \DateTime();
        $publication->setDate($date);
        if ($Form->isSubmitted())
        {
            $em->flush();
            return $this->redirectToRoute('publication');
        }
        return $this->render('@Publication/front/modifier.html.twig',array('form'=>$Form->createView(),'commandes'=>$commandes));
    }


    function supprimerpublicationAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $publication=$em->getRepository(Publication::class)->find($id);
        $em->remove($publication);
        $em->flush();
        return $this->redirectToRoute('publication');
    }


    function DeletePublicationAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $publication=$em->getRepository(Publication::class)->find($id);
        $em->remove($publication);
        $em->flush();
        return $this->redirectToRoute('afficher_publication');
    }


    function RechercheAction(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em1=$this->getDoctrine()->getManager();
        $commandes = $em1->getRepository('ShopBundle:Commande')->findBy(array('idClient'=>$user));
        $publication=new Publication();
        $em=$this->getDoctrine()->getManager();
        $Form=$this->createFormBuilder($publication)
            ->add('description')
            ->add('Recherche',SubmitType::class)
            ->getForm();
        $Form->handleRequest($request);
        if ($Form->isSubmitted())
        {
            $publication=$em->getRepository(Publication::class)
                ->findByDQL($publication->getDescription());
        }
        return $this->render('@Publication/front/recherche.html.twig',array('p'=>$publication,'Fr'=>$Form->createView(),'commandes'=>$commandes));

    }

    function VoteLikeAction($id)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em1=$this->getDoctrine()->getManager();
        $commandes = $em1->getRepository('ShopBundle:Commande')->findBy(array('idClient'=>$user));
        $em=$this->getDoctrine()->getManager();
        $vote=new Vote();
        $votes=$em->getRepository(Vote::class)->findAll();
        $user=$this->getUser();
        $publication=$em->getRepository(Publication::class)->find($id);


        $vote->setIdUser($this->getUser());
        $vote->setIdPublication($publication);
        $vote->setDislikeReaction(0);
        $em->persist($vote);
        $em->flush();

        $publication= $em->getRepository("PublicationBundle:Publication")->findAll();
        return $this->redirectToRoute('publication');
       // return $this->render('@Publication/front/listePublication.html.twig', array('publications' => $publication,'vote'=>$votes,'user'=>$user));

    }

    function VoteDisikeAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $votes=$em->getRepository(Vote::class)->findAll();
        $user=$this->getUser();

        $publication=$em->getRepository(Publication::class)->find($id);
        $vote=new Vote();
        $vote->setIdUser($this->getUser());
        $vote->setIdPublication($publication);
        $vote->setDislikeReaction(1);
        $em->persist($vote);
        $em->flush();
        $publication= $em->getRepository("PublicationBundle:Publication")->findAll();
        return $this->redirectToRoute('publication');
    //    return $this->render('@Publication/front/listePublication.html.twig', array('publications' => $publication,'vote'=>$votes,'user'=>$user));

    }
}
