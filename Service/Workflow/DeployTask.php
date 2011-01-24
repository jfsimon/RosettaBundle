<?php

namespace Bundle\RosettaBundle\Service\Workflow;

use Bundle\RosettaBundle\Service\Deployer\Deployer;

class ChooseTask implements TaskInterface
{
    protected $domains;

    public function __construct(Deployer $deployer)
    {
        $this->domains = array();
    }

    public function handle(array $messages)
    {
        foreach($messages as $message) {
            if(! in_array($message->domain, $this->domains)) {
                $this->domains[] = $message->domain;
            }
        }
    }

    public function process(array $languages)
    {
        foreach($this->domains as $domain) {
            foreach($languages as $locale => $language) {
                $this->deployer->deploy($domain, $language);
            }
        }

        $messages = $this->messages;
        $this->messages = array();

        return $messages;
    }
}