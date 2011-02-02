<?php

namespace Bundle\RosettaBundle\Service\Importer;

use Doctrine\ORM\EntityManager;
use Bundle\RosettaBundle\Service\Locator\Locator;
use Bundle\RosettaBundle\Model\Entity\Domain;
use Bundle\RosettaBundle\Model\Entity\Language;

class Deployer
{
    protected $em;
    protected $locator;
    protected $config;
    protected $adapters;

    public function __construct(EntityManager $em, Locator $locator, array $config)
    {
        $this->em = $em;
        $this->locator = $locator;
        $this->adapters = $config['adapters'];

        unset($config['adapters']);
        $this->config = $config;
    }

    public function deployFile(Domain $domain, Language $language, $adapter = null)
    {
        $messages = $this->em
            ->getRepository('Bundle\\RosettaBundle\\Model\\Entity\\Message')
            ->findBy(array('domain' => $domain, 'language' => $language));

        $adapter = $this->getAdapter($adapter);
        $filename = $this->getFilename($domain->getName(), $language->getCode());
        $translations = $this->getTranslations($messages);

        $adapter->dump($translations, $filename);
    }

    public function deployDomain(Domain $domain, $adapter = null)
    {
        $languages = $this->em
            ->getRepository('Bundle\\RosettaBundle\\Model\\Entity\\Language')
            ->getAll();

        foreach ($languages as $language) {
            $this->deployFile($domain, $language, $adapter);
        }
    }

    public function deployBundle($bundle, $adapter = null)
    {
        $domains = $this->em
            ->getRepository('Bundle\\RosettaBundle\\Model\\Entity\\Domain')
            ->findBy(array('bundle' => $bundle));

        foreach ($domains as $domain) {
            $this->deployDomain($domain, $adapter);
        }
    }

    public function deployProject($adapter = null)
    {
        $domains = $this->em
            ->getRepository('Bundle\\RosettaBundle\\Model\\Entity\\Domain')
            ->getAll();

        foreach ($domains as $domain) {
            $this->deployDomain($domain, $adapter);
        }
    }

    protected function getAdapter($adapter = null)
    {
        $adapter = $adapter ?: $this->config['adapter'];
        $class = $this->adapters[$adapter];

        return new $class();
    }

    protected function getFilename($domain, $locale)
    {
        return $this->locator->locateBundle()
            .'/Resources/translation/'
            .$domain.'_'.$locale;
    }

    protected function getTranslations(array $messages)
    {
        $output = array();

        foreach ($messages as $message) {
            $translation = $this->em
                ->getRepository('Bundle\\RosettaBundle\\Model\\Entity\\Domain')
                ->getChoosenTranslation($message);

            if ($translation) {
                $output[$message->getText()] = $translation->getText();
            }
        }

        return $output;
    }
}