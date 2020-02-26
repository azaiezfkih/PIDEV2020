<?php

namespace AuditionBundle\Controller;



use AuditionBundle\AuditionBundle;
use AuditionBundle\Entity\Audition;
use AuditionBundle\Form\AuditionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;




class AuditionController extends Controller
{
    public function ajouterAuditionAction(Request $request )
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em1=$this->getDoctrine()->getManager();
        $commandes = $em1->getRepository('ShopBundle:Commande')->findBy(array('idClient'=>$user));
        $audition=new Audition();
        $audition->setDate(new \DateTime('now'));

        $form = $this->createForm('AuditionBundle\Form\AuditionningType',$audition);
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('photo')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $audition->setPhoto($newFilename);
            }
            $audition->setUser($this->getUser());
            $audition->setQualified(0);
            $audition->setChecked(0);
            $em=$this->getDoctrine()->getManager();
            $em->persist($audition);
            $em->flush();
            return $this->redirectToRoute('audition_showwpage');

        }
        return $this->render('@Audition/audition/ajouterAudition.html.twig',array('form'=>$form->createView(),'commandes'=>$commandes));
    }


    public function listerAuditionAction()
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em1=$this->getDoctrine()->getManager();
        $commandes = $em1->getRepository('ShopBundle:Commande')->findBy(array('idClient'=>$user));
        $em=$this->getDoctrine()->getManager();
        $auditions=$em->getRepository("AuditionBundle:Audition")->findAll();
        return $this->render("@Audition/audition/listerAudition.html.twig",array('auditions'=>$auditions,'commandes'=>$commandes));
    }

    public function showAuditionAction()
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em1=$this->getDoctrine()->getManager();
        $commandes = $em1->getRepository('ShopBundle:Commande')->findBy(array('idClient'=>$user));
        $audition= $this->getDoctrine()->getRepository(Audition::class)->findBy(array('user'=>$user));
        // $audition=$this->getDoctrine()->getRepository("AuditionBundle:Audition")->find($id);
        return $this->render("@Audition/audition/showAudition.html.twig",array('audition'=>$audition,'commandes'=>$commandes));
    }

    public function supprimerAuditionAction(Request $request, $id)
    {
        $audition= new Audition();
        $em=$this->getDoctrine()->getManager();
        $audition=$em->getRepository("AuditionBundle:Audition")->findOneById($id);
        $em->remove($audition);
        $em->flush();
        return $this->redirectToRoute("audition_showpage");
    }

    public function modifierAuditionAction(Request $request, $id)
    {
        $em=$this->getDoctrine()->getManager();
        $audition=$em->getRepository("AuditionBundle:Audition")->find($id);
        $form=$this->createForm(AuditionType::class,$audition);
        $form->handleRequest($request);
        if ($form->isSubmitted())
        {
            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('photo')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('photos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $audition->setPhoto($newFilename);
            }
            $em->persist($audition);
            $em->flush();
            return $this->redirectToRoute("audition_showpage");
        }
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em1=$this->getDoctrine()->getManager();
        $commandes = $em1->getRepository('ShopBundle:Commande')->findBy(array('idClient'=>$user));
        return $this->render("@Audition/audition/modifierAudition.html.twig",array('form'=>$form->createView(),'commandes'=>$commandes));

    }

    public function statAction()
    {
        /*$audition= new Audition();
        $em=$this->getDoctrine()->getManager();
        $audition=$em->getRepository("AuditionBundle:Audition")->findOneById($id);
        $em->remove($audition);
        $em->flush();*/
        $max=$this->getDoctrine()->getRepository(Audition::class)->stat4DQL();

        return $this->render('@Audition/audition/test.html.twig',array('maxs'=>$max));
    }


}
