<?php

namespace Bundle\RosettaBundle\Service\Importer;

use Symfony\Component\Translation\MessageCatalogue;

class CataloguesManager
{
    protected $loaders;
    protected $catalogues;

    public function __construct(array $loaders)
    {
        $this->loaders = $loaders;
        $this->catalogues = array();
    }

    public function load($resource, $bundle, $domain, $locale, $format)
    {
        if(! isset($this->loaders[$format])) {
            throw new \RuntimeException(sprintf('The "%s" translation loader is not registered.', $format));
        }

        $this->add($bundle, $locale, $this->loaders[$format]->load($resource, $locale, $domain));
    }

    public function reset()
    {
        $this->catalogues = array();
    }

    public function all()
    {
        return $this->catalogues;
    }

    protected function add($bundle, $locale, MessageCatalogue $catalogue)
    {
        if(! isset($this->catalogues[$bundle])) {
            $this->catalogues[$bundle] = array();
        }

        if(isset($this->catalogues[$bundle][$locale])) {
            $this->catalogues[$bundle][$locale]->addCatalogue($catalogue);
        } else {
            $this->catalogues[$bundle][$locale] = $catalogue;
        }
    }
}