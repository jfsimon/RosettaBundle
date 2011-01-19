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

        $parameters = $container->getParameter('rosetta.config');

        foreach($parameters as $key => $value) {
            if(isset($config[$key])) {
                if(is_array($value)) {
                    $parameters[$key] = array_merge($value, $config[$key]);
                } else {
                    $parameters[$key] = $config[$key];
                }
            }
        }

        $container->setParameter('rosetta.config', $parameters);
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
}
