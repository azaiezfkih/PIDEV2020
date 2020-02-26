<?php

namespace EvenementBundle\Controller;

use Cassandra\Date;
use Doctrine\DBAL\Types\DateType;
use Doctrine\ORM\EntityManagerInterface;
use EvenementBundle\Entity\Evenement;
use EvenementBundle\Form\EvenementType;
use Knp\Bundle\PaginatorBundle\KnpPaginatorBundle;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;

class EvenementController extends Controller
{
    public function ajoutAction(Request $request)
    {   $evenement=new Evenement();
        $Form=$this->createForm(EvenementType::class,$evenement);
        $Form->handleRequest($request);
        if ($Form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($evenement);
            $em->flush();
            return $this->redirectToRoute("afficher_evenement_homepage");
        }

        return $this->render('@Evenement/Evenement/ajouterevenement.html.twig', array('Form'=>$Form->createView()));
    }
    public function afficherAction()
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em1=$this->getDoctrine()->getManager();
        $commandes = $em1->getRepository('ShopBundle:Commande')->findBy(array('idClient'=>$user));
        $em=$this->getDoctrine();
        $d=new \DateTime('now');
        $dateact = $d->format('Y-m-d');
      //  $tab=$em->getRepository(Evenement::class)->findbest($dateact);
        $tab=$em->getRepository(Evenement::class)->findAll();
        $tab1=$em->getRepository(User::class)->findAll();

        return $this->render('@Evenement/Evenement/afficherevenement.html.twig',array('formations'=>$tab,'commandes'=>$commandes));
    }
    public function supprimerAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $club=$em->getRepository(Evenement::class)->find($id);
        $em->remove($club);
        $em->flush();


        return $this->redirectToRoute('afficher_evenement_homepage');
    }

    public function modifierAction(Request $request, $id)
    {

        $em=$this->getDoctrine()->getManager();
        $club=$em->getRepository(Evenement::class)->find($id);
        $form=$this->createForm(EvenementType::class,$club);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em->persist($club);
            $em->flush();
            return $this->redirectToRoute('afficher_evenement_homepage');
        }
        return $this->render('@Evenement/Evenement/modifierevenement.html.twig',array('Form'=>$form->createview()));
    }


    public function chercherAction(Request $request)
    {

        $club = new Evenement();
        $em = $this->getDoctrine()->getManager();
        $Form = $this->createFormBuilder($club)
            ->add('datedebut', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
            ->add('datefin', \Symfony\Component\Form\Extension\Core\Type\DateType::class)
            ->add('Recherche', SubmitType::class)
            ->getForm();
        $Form->handleRequest($request);
        if ($Form->isSubmitted()) {
            $datefin = $Form['datefin']->getData();
            $result = $datefin->format('Y-m-d');
            $datedebut = $Form['datedebut']->getData();
            $result1 = $datedebut->format('Y-m-d');
            $club = $em->getRepository(Evenement::class)
                ->findAllOrderedByName($result1, $result);
        }
        return $this->render('@Evenement/Evenement/rechercher.html.twig', array('formations' => $club, 'form' => $Form->createView()));
    }

    public function listAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $dql   = "SELECT a FROM AcmeMainBundle:Article a";
        $evenement= $em->getRepository('EvenementBundle:Evenement')->findAll();

        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $pagination = $this->get('Knp\Component\Pager\PaginatorInterface');
        $paginator->paginate(
            $evenement,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',5)

        );

        // parameters to template
        return $this->render('@Evenement/Evenement/pagi.html.twig', ['pagination' => $pagination]);
    }
    }
