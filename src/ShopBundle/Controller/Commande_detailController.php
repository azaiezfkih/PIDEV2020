<?php

namespace ShopBundle\Controller;

use ShopBundle\Entity\Commande;
use ShopBundle\Entity\Commande_detail;
use ShopBundle\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;

/**
 * Commande_detail controller.
 *
 * @Route("commande_detail")
 */
class Commande_detailController extends Controller
{
    /**
     * Lists all commande_detail entities.
     *
     * @Route("/", name="commande_detail_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $commande_details = $em->getRepository('ShopBundle:Commande_detail')->findAll();

        return $this->render('commande_detail/index.html.twig', array(
            'commande_details' => $commande_details,
        ));
    }

    /**
     * Creates a new commande_detail entity.
     *
     * @Route("/new/{nom}/{prix}/{prod}", name="commande_detail_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request,$nom,$prix,$prod)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $commande_detail = new Commande_detail();
        $form = $this->createForm('ShopBundle\Form\Commande_detailType', $commande_detail);
        $form->handleRequest($request);

        if (1) {

            $commande_detail->setIdClient($user);
            $commande_detail->setIdProd($em=$this->getDoctrine()->getManager()->getRepository(Produit::class)->findOneBy(array('id'=>$prod)));
            $commande_detail->setPrixProd($prix);
            $commande_detail->setQteProd(1);


            if(($em=$this->getDoctrine()->getManager()->getRepository(Commande::class)->findOneBy(array('idClient'=>$user)))==null)
            {
                $em = $this->getDoctrine()->getManager();
                $commande = new Commande();
                $commande->setIdClient($user);
                $commande->setNbProduit(0);
                $commande->setPrix(0);
                $em->persist($commande);
            }

            $em = $this->getDoctrine()->getManager();
            $cmd=$this->getDoctrine()->getManager()->getRepository(Commande::class)->findOneBy(array('idClient'=>$user));
            $commande_detail->setIdCommande($cmd);
            $em->persist($commande_detail);
            $em->flush();

            $em1=$this->getDoctrine()->getManager();
            $em1->getRepository(Produit::class)->updatedql($em=$this->getDoctrine()->getManager()->getRepository(Produit::class)->findOneBy(array('id'=>$prod)));
            $em1->flush();

            $em2=$this->getDoctrine()->getManager();
            $em2->getRepository(Commande::class)->updateDQL($this->getDoctrine()->getManager()->getRepository(User::class)->findOneBy(array('id'=>$user)),$prix);
            $em2->flush();

            $em3=$this->getDoctrine()->getManager();
            $em3->getRepository(Produit::class)->update2DQL($em=$this->getDoctrine()->getManager()->getRepository(Produit::class)->findOneBy(array('id'=>$prod)));
            $em3->flush();

            return $this->redirectToRoute('produit_index');
        }


      return  $this->indexAction();
    }

    /**
     * Finds and displays a commande_detail entity.
     *
     * @Route("/{id}", name="commande_detail_show")
     * @Method("GET")
     */
    public function showAction(Commande_detail $commande_detail)
    {
        $deleteForm = $this->createDeleteForm($commande_detail);

        return $this->render('commande_detail/show.html.twig', array(
            'commande_detail' => $commande_detail,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing commande_detail entity.
     *
     * @Route("/{id}/edit", name="commande_detail_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Commande_detail $commande_detail)
    {
        $deleteForm = $this->createDeleteForm($commande_detail);
        $editForm = $this->createForm('ShopBundle\Form\Commande_detailType', $commande_detail);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('commande_detail_edit', array('id' => $commande_detail->getId()));
        }

        return $this->render('commande_detail/edit.html.twig', array(
            'commande_detail' => $commande_detail,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a commande_detail entity.
     *
     * @Route("/{id}", name="commande_detail_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Commande_detail $commande_detail)
    {
        $form = $this->createDeleteForm($commande_detail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($commande_detail);
            $em->flush();
        }

        return $this->redirectToRoute('commande_detail_index');
    }

    /**
     * Creates a form to delete a commande_detail entity.
     *
     * @param Commande_detail $commande_detail The commande_detail entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Commande_detail $commande_detail)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('commande_detail_delete', array('id' => $commande_detail->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

}
