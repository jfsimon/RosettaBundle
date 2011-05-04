<?php

namespace BeSimple\RosettaBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RosettaExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        // Get config
        $config = $processor->process($configuration->getConfigTree(), $configs);

        // Setup database
        if (!in_array(strtolower($config['database']), array('orm', 'mongodb'))) {
            throw new \InvalidArgumentException(sprintf('Invalid database driver "%s".', $config['database']));
        }
        $loader->load(sprintf('%s.xml', $config['database']));

        // Setup workflow
        foreach (array('scanner', 'importer', 'live') as $key) {
            $container->setParameter(
                sprintf('besimple_rosetta.%s.tasks', $key),
                $this->getServices(
                    'besimple_rosetta.task',
                    isset($config['tasks'][$key]) ? $config['tasks'][$key] : $config['tasks']['default'],
                    $container
                )
            );
        }

        // Setup adapters
        $container->setParameter(
            'besimple_rosetta.translator.adapter',
            $container->getService(sprintf('besimple_rosetta.translator.%s', $config['translator']))
        );
        $container->setParameter(
            'besimple_rosetta.importer.adapter',
            $container->getService(sprintf('besimple_rosetta.importer.%s', $config['importer']))
        );
        $container->setParameter(
            'besimple_rosetta.scanner.adapters',
            $this->getServices('besimple_rosetta.scanner', $config['scanners'], $container)
        );

        // Setup parameters
        foreach (array('rating', 'keys') as $namespace) {
            foreach ($config[$namespace] as $name => $value) {
                $container->setParameter(sprintf('besimple_rosetta.%s.%s', $namespace, $name), $value);
            }
        }

        // Load services
        foreach (array('model', 'translator', 'locator', 'deployer', 'workflow', 'scanner', 'importer') as $basename) {
            $loader->load(sprintf('%s.xml', $basename));
        }

        // Load live service
        if ($config['live']) {
            $loader->load('live.xml');
        }
    }

    public function getAlias()
    {
        return 'besimple_rosetta';
    }

    private function getServices($namespace, array $names, ContainerBuilder $container)
    {
        $services = array();

        foreach ($names as $name) {
            $tasks[$name] = $container->getService(sprintf('%s.%s', $namespace, $name));
        }

        return $services;
    }
}
