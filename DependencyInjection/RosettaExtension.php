<?php

namespace Bundle\RosettaBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RosettaExtension extends Extension
{

    public function configLoad($config, ContainerBuilder $container)
    {
        if(! $container->hasDefinition('rosetta')) {
            $loader = new XmlFileLoader($container, __DIR__.'/../Resources/config');
            $loader->load('rosetta.xml');
        }

        $services = array('translator', 'locator', 'deployer', 'workflow', 'scanner', 'importer', 'live', 'main');

        foreach($services as $service) {
            if(isset($config[$service])) {
                $key = 'rosetta.'.$service.'.config';
                $container->setParameter($key, $this->mergeConfig($container->getParameter($key), $config[$service]));
            };
        }

        $live = $container->getParameter('rosetta.live.config');

        if($live['enabled']) {
            $container->setParameter('translator.class', $live['translator']);
        }

        $this->setupTranslationLoaders($container);
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

    protected function mergeConfig(array $parameters, array $config)
    {
        foreach($parameters as $key => $value) {
            if(isset($config[$key])) {

                if(is_array($value) && is_array($config[$key])) {
                    $config[$key] = $this->mergeConfig($value, $config[$key]);
                }

                $parameters[$key] = $config[$key];
            }
        }

        return $parameters;
    }

    protected function setupTranslationLoaders(ContainerBuilder $container)
    {
        $loaders = array();

        foreach ($container->findTaggedServiceIds('translation.loader') as $id => $attributes) {
            $loaders($attributes[0]['alias'], $container->get($id));
        }

        $importer = $container->getParameter('rosetta.importer.config');
        $importer['loaders'] = $loaders;

        $container->setParameter('rosetta.importer.config', $importer);
    }
}
