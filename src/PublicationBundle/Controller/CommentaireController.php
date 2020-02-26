<?php

namespace PublicationBundle\Controller;

use PublicationBundle\Entity\Commentaire;
use PublicationBundle\Entity\Publication;
use PublicationBundle\Form\CommentaireType;
use PublicationBundle\Form\PublicationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CommentaireController extends Controller
{

    public function ajoutercommantaireAction($id,Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em1=$this->getDoctrine()->getManager();
        $commandes = $em1->getRepository('ShopBundle:Commande')->findBy(array('idClient'=>$user));
        $commentaire=new Commentaire();
        $form=$this->createForm(CommentaireType::class,$commentaire);
        $form->handleRequest($request);

        $date_current=new \DateTime();
        $commentaire->setDate($date_current);
        $em=$this->getDoctrine()->getManager();
        $publication=$em->getRepository(Publication::class)->findPublication($id);
        if($form->isSubmitted()){
            $commentaire->setIdUser($this->getUser());
            $commentaire->setIdPublication($publication[0]);
            $em->persist($commentaire);
            $em->flush();
            return $this->redirectToRoute('publication');
        };
        return $this->render("@Publication/front/AjouterCommentaire.html.twig",array('form'=>$form->createView(),'commandes'=>$commandes));

    }

    public function ListeCommentaireAction($id)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em1=$this->getDoctrine()->getManager();
        $commandes = $em1->getRepository('ShopBundle:Commande')->findBy(array('idClient'=>$user));
        $em = $this->getDoctrine()->getManager();
        $publications=$em->getRepository(Publication::class)->findPublication($id);
        $commentaires = $em->getRepository("PublicationBundle:Commentaire")->findCommentaire($id);
        return $this->render('@Publication/front/ListeCommentaire.html.twig', array(
            'publications' => $publications,
            'commentaires'=>$commentaires,'commandes'=>$commandes));
    }

    /*public function DisplayCommentaireAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $publications=$em->getRepository(Publication::class)->findPublication($id);
        $commentaires = $em->getRepository(Commentaire::class)->findCommentaire($id);
        return $this->render('@Publication/back/AfficherCommentaire.html.twig', array(
            'publications' => $publications,
            'commentaires'=>$commentaires));
        dump($publications,$commentaires);
        die();
    }*/

    function ModifierCommentaireAction($id,Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em1=$this->getDoctrine()->getManager();
        $commandes = $em1->getRepository('ShopBundle:Commande')->findBy(array('idClient'=>$user));
        $em = $this->getDoctrine()->getManager();
        $commentaire = $em->getRepository(Commentaire::class)->find($id);
        $Form = $this->createForm(CommentaireType::class, $commentaire);
        $Form->handleRequest($request);
        if ($Form->isSubmitted())
        {
            $em->flush();
            return $this->redirectToRoute('publication');
        }
        return $this->render('@Publication/front/ModifierCommentaire.html.twig', array('form' => $Form->createView(),'commandes'=>$commandes));
    }


    function SupprimeCommentaireAction($id)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em1=$this->getDoctrine()->getManager();
        $commandes = $em1->getRepository('ShopBundle:Commande')->findBy(array('idClient'=>$user));
        $em=$this->getDoctrine()->getManager();
        $commentaire=$em->getRepository(Commentaire::class)->find($id);
        $em->remove($commentaire);
        $em->flush();
        return $this->redirectToRoute('publication');

    }

    function DeleteCommentaireAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $commentaire = $em->getRepository(Commentaire::class)->find($id);
        $em->remove($commentaire);
        $em->flush();
        return $this->redirectToRoute('display_commentaire');
    }
}
