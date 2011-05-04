<?php

namespace BeSimple\RosettaBundle\Workflow;

use BeSimple\RosettaBundle\Entity\ModelFactoryInterface;
use BeSimple\RosettaBundle\Model\DomainCollection;
use Symfony\Component\Translation\MessageCatalogue;

class Workflow
{
    private $factory;
    private $tasks;
    private $domains;

    public function __construct(ModelFactoryInterface $factory)
    {
        $this->factory = $factory;
        $this->tasks = new TasksStack();
        $this->domains = new DomainCollection();
    }

    public function pushMessage($bundle, $domain, $text, array $parameters = array(), $isChoice = false, array $translations = array())
    {
        $mDomain = $this->factory->domain($bundle, $domain);
        $mMessage = $this->factory->message($mDomain, $text);

        $mMessage->setIsChoice($isChoice);
        $mMessage->setParameters($parameters);

        foreach ($translations as $locale => $translation) {
            $mTranslation = $this->factory->translation($mMessage, $locale, $translation);

            $mMessage->mergeTranslation($mTranslation);
        }

        $mDomain->mergeMessage($mMessage);

        $this->domains->merge($mDomain);
    }

    public function pushCatalogue($bundle, MessageCatalogue $catalogue)
    {
        foreach ($catalogue->all() as $domain => $messages) {
            $mDomain = $this->factory->domain($bundle, $domain);

            foreach ($messages as $text => $translation) {
                $mMessage = $this->factory->message($mDomain, $text);
                $mTranslation = $this->factory->translation($mMessage, $catalogue->getLocale(), $translation);

                $mMessage->mergeTranslation($mTranslation);
            }

            $mDomain->mergeMessage($mMessage);
        }

        $this->domains->merge($mDomain);
    }

    public function setTasks($tasks)
    {
        $this->tasks->clear();

        foreach ($tasks as $task) {
            $this->tasks->add($task);
        }
    }

    public function run()
    {
        $domains = $this->domains;

        foreach ($this->tasks as $task) {
            $domains = $task->run($domains);
        }

        $this->domains->clear();

        return $domains;
    }
}
