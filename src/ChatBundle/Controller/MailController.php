<?php


namespace ChatBundle\Controller;


use ChatBundle\Entity\Conversation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\Routing\Annotation\Route;


class MailController extends Controller
{


    /**
     * Creates a new conversation entity.
     *
     * @Route("/send/mail", name="mail_new", methods={"GET", "POST"})
     */
    public function mailAction(request $request)
    {


        $form = $this->createFormBuilder()

            ->add('sujet',TextareaType::class)
            ->add('to',EmailType::class)

            ->add('Envoi Email',SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid()){

            $data=$form->getData();


            dump($data);

            $message=\Swift_Message::newInstance()
                ->setSubject('De la part service reclamation TunisianGotTalent')
                ->setFrom('rafaa.lakhdhar@esprit.com')
                ->setTo($data['to'])
                ->setBody(
                    $form->getData()['sujet'],'text/plain'
                );
            $this->get('mailer')->send($message);
            return $this->redirectToRoute('homepage');

        }
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em1=$this->getDoctrine()->getManager();
        $commandes = $em1->getRepository('ShopBundle:Commande')->findBy(array('idClient'=>$user));

        return $this->render('conversation/Mail.html.twig',['form'=>$form->createView(),'commandes'=>$commandes]);

    }

}