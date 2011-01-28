<?php

namespace Bundle\RosettaBundle\Service\Workflow;

use Bundle\RosettaBundle\Model\Entity;

use Doctrine\ORM\EntityManager;
use Bundle\RosettaBundle\Service\Translator\Translator;
use Bundle\RosettaBundle\Service\Deployer\Deployer;

class Workflow
{
    protected $modelManager;
    protected $translator;
    protected $tasks;
    protected $deployer;
    protected $messages;

    public function __construct(EntityManager $entityManager, Translator $translator, Deployer $deployer, array $config)
    {
        $this->modelManager = new ModelManager($entityManager);

        $this->tasks = array(
            'translate' => new $config['translate']($translator),
            'choose' => new $config['choose'](),
            'deploy' => new $config['deploy']($deployer),
        );

        $this->translator = $translator;
        $this->deployer = $deployer;
        $this->messages = array();
    }

    public function handle(Input $input)
    {
        $handle = false;

        $message = $this->modelManager->getMessage(
            $this->modelManager->domain($input->getBundle(), $input->getDomain()),
            $input->getText()
        );

        if (is_null($message)) {
            $message = new Message(
                $input->getText(),
                $this->modelManager->domain($input->getBundle(), $input->getDomain()),
                $input->getIsChoice(),
                $input->getParameters(),
                $input->getIsLive()
            );

            $handle = true;
        }

        if ($input->hasTranslations()) {
            foreach ($input->getTranslations() as $locale => $text) {
                $message->addTranslation(new Translation(
                    $message,
                    $this->modelManager->language($locale),
                    $text
                ));
            }

            $handle = true;
        }

        if ($handle) {
            $this->messages[] = $message;
        }
    }

    public function process(array $tasks)
    {
        $tasks = array_merge(array(
            'translate' => false,
            'choose' => false,
            'deploy' => false,
        ), $tasks);

        $messages = $this->messages;
        $this->messages = array();

        foreach ($tasks as $task => $process) {
            if ($process) {
                $this->tasks[$task]->handle($messages);
                $messages = $this->tasks[$task]->process($this->modelManager->allLanguages());
            }
        }

        foreach ($messages as $message) {
            $this->modelManager->addMessage($message);
        }

        $this->modelManager->persist();
    }
}