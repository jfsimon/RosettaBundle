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

    public function __construct(EntityManager $em, Locator $locator, array $config)
    {
        $this->em = $em;
        $this->locator = $locator;
        $this->config = $config;
    }

    public function deployFile(Domain $domain, Language $language)
    {
        $messages = $this->em
            ->getRepository('Bundle\\RosettaBundle\\Model\\Entity\\Message')
            ->findBy(array('domain' => $domain, 'language' => $language));

    }

    public function deployDomain()
    {

    }

    public function deployBundle($bundle)
    {

    }

    public function importProject()
    {

    }
}