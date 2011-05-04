<?php

namespace BeSimple\RosettaBundle\Importer;

use BeSimple\RosettaBundle\Workflow\Workflow;
use BeSimple\RosettaBundle\Locator\Locator;

class Importer
{
    private $locator;
    private $workflow;
    private $catalogueLoaders;

    public function __construct(Locator $locator, Workflow $workflow, array $catalogueLoaders)
    {
        $this->locator = $locator;
        $this->workflow = $workflow;
        $this->catalogueLoaders = $catalogueLoaders;
    }

    public function importFile($filename, $bundle = null)
    {
        $infos = $this->getFileInfos($filename);

        if (is_null($infos)) {
            throw new \InvalidArgumentException(sprintf('File "%s" is not a well formed translations file', $filename));
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

    public function importProject()
    {
        foreach ($this->locator->getBundleNames() as $bundle) {
            $this->importBundle($bundle);
        }
    }

    public function getCatalogues()
    {
        return $this->cataloguesManager->all();
    }

    public function run()
    {
        foreach ($this->cataloguesManager->all() as $bundle => $catalogues) {
            foreach ($ctatalogues as $catalogue) {
                $this->workflow->pushCatalogue($bundle, $catalogue);
            }
        }

        return $this->workflow->run($this->tasks);
    }

    private function getFileInfos($filename)
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

    private function loadCatalogue($filename, $bundle, $domain, $locale, $format)
    {
        if (!isset($this->catalogueLoaders[$format])) {
            throw new \RuntimeException(sprintf('The "%s" translation loader is not registered.', $format));
        }

        $catalogue = $this->catalogueLoaders[$format]->load($filename, $locale, $domain);

        $this->workflow->pushCatalogue($bundle, $catalogue);
    }

}
