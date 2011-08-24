<?php

namespace BeSimple\RosettaBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class ParametersGuesserPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $wrapper = null;
        $wrapped = array();

        foreach ($container->findTaggedServiceIds('be_simple_rosetta.parameters_guesser') as $id => $attributes) {
            $definition = $container->getDefinition($id);
            $alias      = explode('.', $attributes[0]['alias']);

            if ($alias[0] === 'wrapper') {
                $wrapper = $definition;
            } else {
                $wrapped[] = $definition;
            }
        }

        if (!is_null($wrapper)) {
            $wrapper->addArgument($wrapped);
        }
    }
}
