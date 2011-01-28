<?php

namespace Bundle\RosettaBundle\Service\Importer;

use Symfony\Component\Translation\MessageCatalogue;
use Bundle\RosettaBundle\Service\Workflow\Workflow;
use Bundle\RosettaBundle\Service\Workflow\Input;

class WorkflowManager
{
    protected $workflow;

    public function __construct(Workflow $workflow)
    {
        $this->workflow = $workflow;
    }

    public function addCatalogues($bundle, array $catalogues)
    {
        foreach($catalogues as $locale => $catalogue) {
            $this->addCatalogue($bundle, $locale, $catalogue);
        }
    }

    public function addCatalogue($bundle, $locale, MessageCatalogue $catalogue)
    {
        $inputs = array();

        foreach($catalogue->all() as $domain => $messages) {
            foreach($messages as $id => $translation) {
                $inputs[$id] = new Input(
                    $id,
                    $this->guessMessageParameters($id),
                    $domain,
                    $bundle,
                    $this->guessIfMessageIsChoice($id),
                    false
                );

                $inputs[$id]->addTranslation($locale, $translation);
            }
        }

        foreach ($inputs as $input) {
            $this->workflow->handle($input);
        }
    }

    public function process(array $tasks)
    {
        return $this->workflow->process($tasks);
    }

    protected function guessMessageParameters($id)
    {
        $parameters = array();

        foreach (array('/(\{\{\s*[^}\s]+\s*\}\})/', '/(%[^%\s]+%)/') as $regexp) {
            if(preg_match_all($regexp, $id, $matches, PREG_SET_ORDER)) {
                foreach($matches as $match) {
                    $parameters[] = $match[1];
                }
            }
        }

        return $parameters;
    }

    protected function guessIfMessageIsChoice($id)
    {
        return preg_match('/|[{[]].+[}[]]/', $id);
    }
}