<?php

namespace ShopBundle\Controller;

use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use ShopBundle\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;


/**
 * Produit controller.
 *
 * @Route("produitF")
 */
class ProduitController extends Controller
{
    /**
     * Lists all produit entities.
     *
     * @Route("/test", name="produit_test")
     * @Method("GET")
     */
    public function testAction()
    {

        //Pie chart quantite
        $pieChart = new PieChart();
        $em= $this->getDoctrine();
        $classes = $em->getRepository(Produit::class)->findAll();
        $totalEtudiant=0;
        foreach($classes as $classe) {
            $totalEtudiant=$totalEtudiant+$classe->getQuantite();
        }

        $data= array();
        $stat=['classe', 'nbEtudiant'];
        $nb=0;
        array_push($data,$stat);
        foreach($classes as $classe) {
            $stat=array();
            array_push($stat,$classe->getNom(),(($classe->getQuantite()) *100)/$totalEtudiant);
            $nb=($classe->getQuantite() *100)/$totalEtudiant;
            $stat=[$classe->getNom(),$nb];
            array_push($data,$stat);

        }

        $pieChart->getData()->setArrayToDataTable(
            $data
        );
        $pieChart->getOptions()->setTitle('Pourcentages des produits par quantite');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);
       //Pie chart nb vente

        $pieChart2 = new PieChart();
        $em= $this->getDoctrine();
        $classes1 = $em->getRepository(Produit::class)->findAll();
        $totalEtudiant1=0;
        foreach($classes1 as $classe) {
            $totalEtudiant1=$totalEtudiant+$classe->getNbVente();
        }

        $data1= array();
        $stat1=['classe1', 'nbEtudiant1'];
        $nb1=0;
        array_push($data1,$stat1);
        foreach($classes1 as $classe) {
            $stat1=array();
            array_push($stat1,$classe->getNom(),(($classe->getNbVente()) *100)/$totalEtudiant1);
            $nb1=($classe->getNbVente() *100)/$totalEtudiant1;
            $stat1=[$classe->getNom(),$nb1];
            array_push($data1,$stat1);

        }

        $pieChart2->getData()->setArrayToDataTable(
            $data1
        );
        $pieChart2->getOptions()->setTitle('Pourcentages des ventes par produits');
        $pieChart2->getOptions()->setHeight(500);
        $pieChart2->getOptions()->setWidth(900);
        $pieChart2->getOptions()->setBackgroundColor(5244);
        $pieChart2->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart2->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart2->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart2->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart2->getOptions()->getTitleTextStyle()->setFontSize(20);
        $pieChart2->getOptions()->getPieSliceTextStyle()->setFontSize(10);

        $max=$this->getDoctrine()->getRepository(Produit::class)->stat1DQL();
        $totalprix=$this->getDoctrine()->getRepository(Produit::class)->stat2DQL();
       $revenue=$this->getDoctrine()->getRepository(Produit::class)->stat3DQL();
        $nbb=$this->getDoctrine()->getRepository(Produit::class)->stat4DQL();

        return $this->render('produit/test.html.twig', array('piechart' => $pieChart,'piechart2'=>$pieChart2,'maxs'=>$max,'total'=>$totalprix,'revenue'=>$revenue,'nbb'=>$nbb));
    }




    /**
     * Lists all produit entities.
     *
     * @Route("/", name="produit_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em1=$this->getDoctrine()->getManager();
        $em = $this->getDoctrine()->getManager();

        $produits = $em->getRepository('ShopBundle:Produit')->findAll();
        $commandes = $em1->getRepository('ShopBundle:Commande')->findBy(array('idClient'=>$user));
        return $this->render('produit/index.html.twig', array(
            'produits' => $produits,'commandes'=>$commandes
        ));
    }

    /**
     * Creates a new produit entity.
     *
     * @Route("/new", name="produit_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $produit = new Produit();
        $form = $this->createForm('ShopBundle\Form\ProduitType', $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           /**
            * @var UploadedFile $file
            */
           echo $produit->getImage();
           $file=$form->get('image')->getData();
           $fileName=md5(uniqid()).'.'.$file->guessExtension();
           $file->move($this->getParameter('image_directory'),$fileName);
            $produit->setImage($fileName);
            $produit->setNbVente(0);
            $em = $this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();

            return $this->redirectToRoute('produit_show', array('id' => $produit->getId()));
        }





        return $this->render('produit/new.html.twig', array(
            'produit' => $produit,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a produit entity.
     *
     * @Route("/{id}", name="produit_show")
     * @Method("GET")
     */
    public function showAction(Produit $produit)
    {
        $deleteForm = $this->createDeleteForm($produit);
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em1=$this->getDoctrine()->getManager();
        $commandes = $em1->getRepository('ShopBundle:Commande')->findBy(array('idClient'=>$user));
        return $this->render('produit/show.html.twig', array(
            'produit' => $produit,
            'delete_form' => $deleteForm->createView(),'commandes'=>$commandes
        ));
    }

    /**
     * Displays a form to edit an existing produit entity.
     *
     * @Route("/{id}/edit", name="produit_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Produit $produit)
    {
        $deleteForm = $this->createDeleteForm($produit);
        $editForm = $this->createForm('ShopBundle\Form\ProduitType', $produit);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('produit_edit', array('id' => $produit->getId()));
        }

        return $this->render('produit/edit.html.twig', array(
            'produit' => $produit,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a produit entity.
     *
     * @Route("/{id}", name="produit_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Produit $produit)
    {
        $form = $this->createDeleteForm($produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($produit);
            $em->flush();
        }

        return $this->redirectToRoute('produit_index');
    }

    /**
     * Creates a form to delete a produit entity.
     *
     * @param Produit $produit The produit entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Produit $produit)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('produit_delete', array('id' => $produit->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Lists all produit entities.
     *
     * @Route("/rech/{id}", name="produit_rech")
     * @Method("GET")
     */
    public function rechAction($id)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em1=$this->getDoctrine()->getManager();
        $em = $this->getDoctrine()->getManager();

        $produits = $em->getRepository('ShopBundle:Produit')->FindByDQL($id);
        $commandes = $em1->getRepository('ShopBundle:Commande')->findBy(array('idClient'=>$user));
        return $this->render('produit/index.html.twig', array(
            'produits' => $produits,'commandes'=>$commandes
        ));
    }



}
