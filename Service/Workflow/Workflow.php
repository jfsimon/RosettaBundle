<?php

namespace Bundle\RosettaBundle\Service\Workflow;

class Workflow
{
    protected $em;
    protected $translator;
    protected $deployer;
    protected $repositories;
    protected $messages;
    protected $locales;

    public function __construct(EntityManager $em, Translator $translator, Deployer $deployer)
    {
        $this->em = $em;
        $this->translator = $translator;
        $this->deployer = $deployer;

        $this->repositories = array(
            'language' => $this->em->getRepository('Bundle\\RosettaBundle\\Model\\Entity\\Language'),
            'domain' => $this->em->getRepository('Bundle\\RosettaBundle\\Model\\Entity\\Domain'),
            'message' => $this->em->getRepository('Bundle\\RosettaBundle\\Model\\Entity\\Message'),
            'translation' => $this->em->getRepository('Bundle\\RosettaBundle\\Model\\Entity\\Translation'),
        );
    }

    public function handle(Input $input)
    {
        $domain = $this->repositories['domain']->getOrCreate($input->getBundle(), $input->getDomain());

        if($this->repositories['message']->has($domain, $input->getText())) {
            return;
        }

        $language = $this->repositories['language']->getOrCreate($input->getLocale());
        $message = $this->repositories['message']->getOrCreate($domain, $input->getText());

        $message->setIsChoice($input->getIsChoice());
        $message->setIsLive($input->getIsLive());
        $message->setParameters($input->getParameters());

        if(! $language->getId()) {
            $this->em->persist($language);
        }

        if(! $domain->getId()) {
            $this->em->persist($domain);
        }

        $this->messages[] = $message;
    }

    public function process(array $tasks)
    {
        $tasks = array_merge(array(
            'translate' => false,
            'choose' => false,
            'deploy' => false,
        ), $tasks);

        $this->locales = $this->repositories['language']->getCodes();

        foreach($this->launchTasks($tasks) as $message) {
            $this->em->persist($message);
        }

        $this->em->flush();
        $this->messages = array();
    }

    protected function launchTasks(array $tasks)
    {
        $messages = $this->messages;

        if($tasks['translate']) {
            foreach($messages as $index => $message) {
                $messages[$index] = $this->translateMessage($message);
            }
        } else {
            return $messages;
        }

        if($tasks['choose']) {
            foreach($messages as $index => $message) {
                $messages[$index] = $this->chooseMessage($message);
            }
        } else {
            return $messages;
        }

        if($tasks['deploy']) {
            return $this->deployMessages($messages);
        }

        return $messages;
    }

    protected function translateMessage(Message $message)
    {
        $translations = $this->translator->translate($message->getText(), $this->locales);

        foreach($translations as $translation) {
            $this->em->persist($translation);
            $message->addTranslation($translation);
        }

        return $message;
    }

    protected function chooseMessage(Message $message)
    {
        foreach($this->locales as $locale) {
            if(! $his->repositories['translation']->getChoosen($message, $locale)) {
                $translation = $his->repositories['translation']->chooseBest($message, $locale);

                if($translation) {
                    $this->em->persist($translation);
                }
            }
        }

        return $message;
    }

    protected function deployMessages(array $messages)
    {
        $bundles = array();

        foreach($messages as $message) {
            if(! isset($bundles[$message->getBundle()])) {
                $bundles[$message->getBundle()] = array();
            }

            if(! in_array($message->getDomain(), $bundles[$message->getBundle()])) {
                $this->deployer->deployDomain($this->model, $message->getBundle(), $message->getDomain());
                $bundles[$message->getBundle()][] = $message->getDomain();
            }
        }

        return $messages;
    }
}