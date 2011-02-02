<?php

namespace Bundle\RosettaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    public function indexAction()
    {
        return $this->render('RosettaBundle:Admin:index.twig');
    }

    public function menuAction()
    {
        $domains = $this->get('doctrine.orm.entity_manager')
            ->getRepository('Bundle\\RosettaBundle\\Model\\Entity\\Domain')
            ->getAdminMenu();

        return $this->render('RosettaBundle:Admin:menu.twig', array('domains' => $domains));
    }

    public function messagesAction($domain)
    {
        $messages = $this->get('doctrine.orm.entity_manager')
            ->getRepository('Bundle\\RosettaBundle\\Model\\Entity\\Message')
            ->getAdminMessages($domain);

        return $this->render('RosettaBundle:Admin:messages.twig', array('messages' => $messages));
    }

    public function translationAction($message)
    {
        $message = $this->get('doctrine.orm.entity_manager')
            ->getRepository('Bundle\\RosettaBundle\\Model\\Entity\\Message')
            ->getAdminTranslations($message);

        return $this->render('RosettaBundle:Admin:messages.twig', array('message' => $message));
    }
}