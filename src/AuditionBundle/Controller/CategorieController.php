<?php


namespace AuditionBundle\Controller;


use AuditionBundle\Entity\Audition;
use AuditionBundle\Entity\Categorie;
use AuditionBundle\Form\AuditionType;
use AuditionBundle\Form\CategorieType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CategorieController extends Controller
{
    public function ajouterCategorieAction(Request $request )
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em1=$this->getDoctrine()->getManager();
        $commandes = $em1->getRepository('ShopBundle:Commande')->findBy(array('idClient'=>$user));
        $categorie=new Categorie();
        $form = $this->createForm('AuditionBundle\Form\CategorieType',$categorie);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();
            return $this->redirectToRoute('categorie_showpage');

        }
        return $this->render('@Audition/categorie/ajouterCategorie.html.twig',array('form'=>$form->createView(),'commandes'));
    }

    public function listerCategorieAction()
    {
        $em=$this->getDoctrine()->getManager();
        $categories=$em->getRepository("AuditionBundle:Categorie")->findAll();
        return $this->render("@Audition/categorie/listerCategorie.html.twig",array('categories'=>$categories));
    }

    public function supprimerCategorieAction(Request $request, $id)
    {
        $categorie= new Categorie();
        $em=$this->getDoctrine()->getManager();
        $categorie=$em->getRepository("AuditionBundle:Categorie")->findOneById($id);
        $em->remove($categorie);
        $em->flush();
        return $this->redirectToRoute("categorie_showpage");
    }

    public function modifierCategorieAction(Request $request, $id)
    {
        $em=$this->getDoctrine()->getManager();
        $categorie=$em->getRepository("AuditionBundle:Categorie")->find($id);
        $form=$this->createForm(CategorieType::class,$categorie);
        $form->handleRequest($request);
        if ($form->isSubmitted())
        {
            $em->persist($categorie);
            $em->flush();
            return $this->redirectToRoute("categorie_showpage");
        }
        return $this->render("@Audition/categorie/modifierCategorie.html.twig",array('form'=>$form->createView()));

    }
}