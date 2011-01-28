<?php

namespace Bundle\RosettaBundle\Service\Workflow;

use Bundle\RosettaBundle\Service\Translator\Translator;
use Bundle\RosettaBundle\Model\Entity\Translation;

class TranslateTask implements TaskInterface
{
    protected $translator;
    protected $messages;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
        $this->messages = array();
    }

    public function handle(array $messages)
    {
        foreach($messages as $message) {
            if(! $message->hasTranslation()) {
                $this->messages[] = $message;
            }
        }
    }

    public function process(array $languages)
    {
        foreach($this->messages as $message) {
            $translations = $this->translator->translate($message->getText(), array_keys($languages));

            foreach($translations as $locale => $translation) {
                $entity = new Translation();
                $entity->setLanguage($this->languages[$locale]);
                $entity->setText($translation);
                $entity->setIsAutomatic(true);

                $message->addTranslation($entity);
            }
        }

        $messages = $this->messages;
        $this->messages = array();

        return $messages;
    }
}