<?php

namespace BeSimple\RosettaBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;
use BeSimple\RosettaBundle\Translation\ParametersGuesser\GlobParametersGuesser;
use BeSimple\RosettaBundle\Translation\ParametersGuesser\RegexpParametersGuesser;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class BeSimpleRosettaExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $config = $this->processConfiguration(new Configuration(), $configs);

        $this->setupTranslationServices($config, $container);
        $loader->load('translation.xml');

        $this->setupRosettaServices($config, $container);
        $loader->load('rosetta.xml');

        $loader->load($config['db_adapter'].'.xml');

        // todo: manage the fact that services can be disabled (is this useful ?)
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @param array            $config
     * @param ContainerBuilder $container
     */
    private function setupTranslationServices(array $config, ContainerBuilder $container)
    {
        $parametersRegexps = array();
        $parametersGlobs   = array();

        foreach ($config['importer']['parameters'] as $pattern) {
            if (preg_match(RegexpParametersGuesser::REGEXP_PATTERN, $pattern)) {
                $parametersRegexps[] = $pattern;
            } else if(preg_match(GlobParametersGuesser::GLOB_PATTERN, $pattern)) {
                $parametersGlobs[] = $pattern;
            } else {
                throw new \InvalidArgumentException(sprintf('"%s" is not a valid importer parameters guesser pattern.', $pattern));
            }
        }

        $container->setParameter('be_simple_rosetta.parameters_guesser.regexps', $parametersRegexps);
        $container->setParameter('be_simple_rosetta.parameters_guesser.globs', $parametersGlobs);

        $container->setParameter('be_simple_rosetta.translator.options', $config['translator']['options']);
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     */
    private function setupRosettaServices(array $config, ContainerBuilder $container)
    {
        $container->setParameter('be_simple_rosetta.factory.defaults', array(
            'dumper'     => $config['dumper']['format'],
            'translator' => $config['translator']['adapter'],
        ));

        $container->setParameter('be_simple_rosetta.locator.bundles', $config['manage']['bundles']);
        $container->setParameter('be_simple_rosetta.locator.app_dir', $config['manage']['app_dir']);
        $container->setParameter('be_simple_rosetta.locator.src_dir', $config['manage']['src_dir']);
    }
}
