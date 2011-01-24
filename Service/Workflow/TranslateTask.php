<?php

namespace Bundle\RosettaBundle\Service\Workflow;

use Bundle\RosettaBundle\Service\Translator\Translator;

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
                $message->addTranslation($this->languages[$locale], $translation);
            }
        }

        $messages = $this->messages;
        $this->messages = array();

        return $messages;
    }
}