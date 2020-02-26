<?php

namespace ChatBundle\Controller;

use ChatBundle\Entity\Conversation;
use ChatBundle\Entity\Message;
use ChatBundle\Form\MessageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use \Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Conversation controller.
 *
 * @Route("conversation")
 */
class ConversationController extends Controller
{
    /**
     * Lists all conversation entities.
     *
     * @Route("/", name="conversation_index", methods={"GET"})
     */
    public function indexAction(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em1=$this->getDoctrine()->getManager();
        $commandes = $em1->getRepository('ShopBundle:Commande')->findBy(array('idClient'=>$user));
        $em = $this->getDoctrine()->getManager();
        $listeconversations = $em->getRepository('ChatBundle:Conversation')->findAll();
        $conversations  = $this->get('knp_paginator')->paginate(
            $listeconversations,
            $request->query->get('page', 1)/*le numéro de la page à afficher*/,
            6/*nbre d'éléments par page*/);

        return $this->render('conversation/index.html.twig', array(
            'conversations' => $conversations,
            'commandes'=>$commandes
        ));
    }

    /**
     * Lists all conversation entities.
     *
     * @Route("/chat/{id}", name="conversation_chat", methods={"GET"})
     * @param Conversation $conversation
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function chatAction(Conversation $conversation,Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em1=$this->getDoctrine()->getManager();
        $commandes = $em1->getRepository('ShopBundle:Commande')->findBy(array('idClient'=>$user));
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $listeconversations = $em->getRepository('ChatBundle:Conversation')->findByUsers($user->getId());
        $conversations  = $this->get('knp_paginator')->paginate(
            $listeconversations,
            $request->query->get('page', 1),
            6/*nbre d'éléments par page*/);

        return $this->render('conversation/chat.html.twig', array(
            'conversationsUser' => $conversations,
            'conversation' => $conversation,
            'currentUser' => $this->getUser(),
            'commandes'=>$commandes

                    ));
    }

    /**
     * Lists all conversation entities.
     *
     * @Route("/chat/{id}", name="conversation_chat_send", methods={"POST"})
     * @param Conversation $conversation
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sentMessageAction(Conversation $conversation, Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em1=$this->getDoctrine()->getManager();
        $commandes = $em1->getRepository('ShopBundle:Commande')->findBy(array('idClient'=>$user));
        $message = new Message();
        $message->setUser($this->getUser());
        $message->setConversation($conversation);
        $message->setVisible(true);

        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        $message->setMessage($request->request->get('message'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($message);
        $em->flush();


        return $this->redirectToRoute('conversation_chat', ['id' => $conversation->getId(),'commandes'=>$commandes]);
    }

    /**
     * Creates a new conversation entity.
     *
     * @Route("/new", name="conversation_new", methods={"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em1=$this->getDoctrine()->getManager();
        $commandes = $em1->getRepository('ShopBundle:Commande')->findBy(array('idClient'=>$user));
        $conversation = new Conversation();
        $form = $this->createForm('ChatBundle\Form\ConversationType', $conversation,array('user' => $this->getUser()));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $conversation->setDateCreation(new \DateTime('now'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($conversation);
            $em->flush();

            return $this->redirectToRoute('conversation_chat', array('id' => $conversation->getId(),'commandes'=>$commandes));
        }

        return $this->render('conversation/new.html.twig', array(
            'conversation' => $conversation,
            'form' => $form->createView(),
            'commandes'=>$commandes
        ));
    }

    /**
     * Finds and displays a conversation entity.
     *
     * @Route("/{id}", name="conversation_show", methods={"GET"})
     */
    public function showAction(Conversation $conversation)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em1=$this->getDoctrine()->getManager();
        $commandes = $em1->getRepository('ShopBundle:Commande')->findBy(array('idClient'=>$user));
        $deleteForm = $this->createDeleteForm($conversation);
        return $this->render('conversation/show.html.twig', array(
            'conversation' => $conversation,
            'delete_form' => $deleteForm->createView(),
            'commandes'=>$commandes
        ));
    }

    /**
     * Displays a form to edit an existing conversation entity.
     *
     * @Route("/{id}/edit", name="conversation_edit", methods={"GET", "POST"})
     */
    public function editAction(Request $request, Conversation $conversation)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em1=$this->getDoctrine()->getManager();
        $commandes = $em1->getRepository('ShopBundle:Commande')->findBy(array('idClient'=>$user));
        $deleteForm = $this->createDeleteForm($conversation);
        $editForm = $this->createForm('ChatBundle\Form\ConversationType', $conversation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('conversation_chat', array('id' => $conversation->getId()));
        }

        return $this->render('conversation/edit.html.twig', array(
            'conversation' => $conversation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'commandes'=>$commandes
        ));
    }

    /**
     * Deletes a conversation entity.
     *
     * @Route("/{id}", name="conversation_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, Conversation $conversation)
    {

        $form = $this->createDeleteForm($conversation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($conversation);
            $em->flush();
        }

        return $this->redirectToRoute('conversation_index');
    }

    /**
     * Creates a form to delete a conversation entity.
     *
     * @param Conversation $conversation The conversation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Conversation $conversation)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em1=$this->getDoctrine()->getManager();
        $commandes = $em1->getRepository('ShopBundle:Commande')->findBy(array('idClient'=>$user));
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('conversation_delete', array('id' => $conversation->getId(),'commandes'=>$commandes)))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }
}
