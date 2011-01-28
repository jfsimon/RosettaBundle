<?php

namespace Bundle\RosettaBundle\Service\Importer;

use Bundle\RosettaBundle\Service\Workflow\Workflow;
use Bundle\RosettaBundle\Service\Locator\Locator;
use Bundle\RosettaBundle\Service\Workflow\Input;

class Importer
{
    protected $locator;
    protected $workflowManager;
    protected $cataloguesManager;

    /**
     * Constructor.
     *
     * @param $locator  Bundle\RosettaBundle\Service\Locator\Locator
     * @param $workflow Bundle\RosettaBundle\Service\Workflow\Workflow
     * @param $config   array
     */
    public function __construct(Locator $locator, Workflow $workflow, array $config)
    {
        $this->locator = $locator;
        $this->workflowManager = new WorkflowManager($workflow);
        $this->cataloguesManager = new CataloguesManager($config['loaders']);

        unset($config['loaders']);
        $this->tasks = $config;
    }

    public function importFile($filename, $bundle=null)
    {
        $infos = $this->getFileInfos($filename);

        if (is_null($infos)) {
            return null;
        }

        if (is_null($bundle)) {
            $bundle = $this->locator->guessBundleFromPath($filename);
        }

        $this->cataloguesManager->load($filename, $bundle, $infos['domain'], $infos['locale'], $infos['format']);
    }

    public function importBundle($bundle)
    {
        foreach ($this->locateTranslationFiles($bundle) as $filename) {
            $this->importFile($filename, $bundle);
        }
    }

    public function importProject($workflow=true)
    {
        foreach ($this->locator->getBundleNames() as $bundle) {
            $this->importBundle($bundle);
        }
    }

    public function getCatalogues()
    {
        return $this->catalogues->all();
    }

    public function process()
    {
        foreach($this->cataloguesManager->all() as $bundle => $catalogues) {
            $this->workflowManager->addCatalogues($bundle, $catalogues);
        }

        return $this->workflowManager->process($this->tasks);
    }

    protected function getFileInfos($filename)
    {
        $infos = explode('.', basename($filename));

        if (count($infos) !== 3) {
            return null;
        }

        return array(
            'domain' => $infos[0],
            'locale' => $infos[1],
            'format' => $infos[2]
        );
    }

}