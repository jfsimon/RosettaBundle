<?php

namespace BeSimple\RosettaBundle\Tests;

use Symfony\Component\HttpKernel\Util\Filesystem;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    /**
     * @var string
     */
    private $tempDir;

    /**
     * @var string
     */
    private $configFile;

    /**
     * Constructor.
     *
     * @param string $tmpDirectory
     * @param string $configFile
     * @param string $environment
     * @param bool   $debug
     */
    public function __construct($tempDir, $configFile, $environment, $debug)
    {
        $this->tempDir    = $tempDir;
        $this->configFile = $configFile;

        parent::__construct($environment, $debug);
    }

    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        $bundles = array(
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Symfony\Bundle\DoctrineBundle\DoctrineBundle(),
            new \BeSimple\RosettaBundle\BeSimpleRosettaBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new \Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
        }

        return $bundles;
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getRootDir()
    {
        return __DIR__;
    }

    public function getTempDir()
    {
        return $this->tempDir;
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheDir()
    {
        return $this->tempDir.'/cache';
    }

    /**
     * {@inheritdoc}
     */
    public function getLogDir()
    {
        return $this->tempDir.'/logs';
    }

    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->configFile);
    }
}
