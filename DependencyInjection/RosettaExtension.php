<?php

namespace Bundle\RosettaBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RosettaExtension extends Extension
{

    public function configLoad($config, ContainerBuilder $container)
    {
        $this->loadDefinition($container);

        $options = array();
        foreach(array('store', 'translate') as $option) {
            if(isset($config[$option])) {
                $options[$options] = (bool)$config[$option];
            }
        }

        $options = array_merge($container->getParameter('rosetta.options'), $options);
        $container->setParameter('rosetta.options', $options);
    }

    public function scannersLoad($config, ContainerBuilder $container)
    {
        $this->loadDefinition($container);

        $scanners = array();
        foreach($config as $extension => $class) {
            $scanners[$extension] = $class;
        }

        $scanners = array_merge($container->getParameter('rosetta.scanner.scanners'), $scanners);
        $container->setParameter('rosetta.scanner.scanners', $scanners);
    }

    public function liveLoad($config, ContainerBuilder $container)
    {
        $this->loadDefinition($container);

        $options = array('enabled' => true);
        foreach(array('enabled', 'store', 'translate', 'choose') as $option) {
            if(isset($config[$option])) {
                $options[$options] = (bool)$config[$option];
            }
        }

        $options = array_merge($container->getParameter('rosetta.live.options'), $options);
        $container->setParameter('rosetta.live.options', $options);
    }

    public function getXsdValidationBasePath()
    {
        return __DIR__.'/../Resources/config/schema';
    }

    public function getNamespace()
    {
        return 'http://www.symfony-project.org/schema/dic/rosetta';
    }

    public function getAlias()
    {
        return 'rosetta';
    }

    protected function loadDefinition(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('rosetta')) {
            $loader = new XmlFileLoader($container, __DIR__.'/../Resources/config');
            $loader->load('rosetta.xml');
        }
    }
}
