<?php

namespace Bundle\RosettaBundle\Service\Workflow;

use Bundle\RosettaBundle\Service\Translator\Deploy;

class DeployTask implements TaskInterface
{
    protected $messages;

    public function __construct()
    {
        $this->messages = array();
    }

    public function handle(array $messages)
    {
        foreach($messages as $message) {
            if(! $message->hasChoosenTranslation()) {
                $this->messages[] = $message;
            }
        }
    }

    public function process(array $languages)
    {
        foreach($this->messages as $message) {
            foreach($languages as $language) {
                $message->chooseBestTrasnlation($language);
            }
        }

        $messages = $this->messages;
        $this->messages = array();

        return $messages;
    }
}