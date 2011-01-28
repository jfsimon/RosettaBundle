<?php

namespace Bundle\RosettaBundle\Service\Workflow;

use Bundle\RosettaBundle\Model\Entity\Language;
use Bundle\RosettaBundle\Model\Entity\Domain;
use Bundle\RosettaBundle\Model\Entity\Message;
use Bundle\RosettaBundle\Model\Entity\Translation;

class ModelManager
{
    protected $entityManager;
    protected $languages;
    protected $domains;
    protected $messages;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->languages = array();
        $this->domains = array();
        $this->messages = array();
    }

    public function language($locale)
    {
        if (!isset($this->languages[$locale])) {
            $language = $this
                ->entityManager
                ->getRepository('Bundle\\RosettaBundle\\Model\\Entity\\Language')
                ->getOrCreate($locale)
            ;

            $this->languages[$locale] = $language;
        }

        return $this->languages[$locale];
    }

    public function domain($bundle, $domain)
    {
        if (!isset($this->domains[$bundle.'/'.$domain])) {
            $domain = $this
                ->entityManager
                ->getRepository('Bundle\\RosettaBundle\\Model\\Entity\\Domain')
                ->getOrCreate($bundle, $domain)
            ;

            $this->domains[$bundle.'/'.$domain] = $domain;
        }

        return $this->domains[$bundle.'/'.$domain];
    }

    public function getMessage(Domain $domain, $text)
    {
        foreach ($domain->getMessages() as $message) {
            if ($message->getText() === $text) {
                return $message;
            }
        }

        return null;
    }

    public function addMessage(Message $message)
    {
        $this->messages[] = $message;
    }

    public function persist($flush = true)
    {
        foreach ($this->languages as $language) {
            $this->entityManager->persist($language);
        }

        foreach ($this->domains as $domain) {
            $this->entityManager->persist($domain);
        }

        foreach ($this->messages as $message) {
            $this->entityManager->persist($message);

            foreach($this->messages->getTranslations() as $translation) {
                $this->entityManager->persist($translation);
            }
        }

        if ($flush) {
            $this->entityManager->flush();

            $this->languages = array();
            $this->domains = array();
        }
    }

    public function allLanguages()
    {
        $languages = array();
        $repository = $this
            ->entityManager
            ->getRepository('Bundle\\RosettaBundle\\Model\\Entity\\Language');

        foreach ($repository->all() as $language) {
            $languages[$language->getCode()] = $language;
        }

        return $languages;
    }
}