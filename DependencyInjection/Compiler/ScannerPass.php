<?php

namespace BeSimple\RosettaBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class ScannerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $scanners = array();
        $wrappers = array();

        foreach ($container->findTaggedServiceIds('be_simple_rosetta.scanner') as $id => $attributes) {
            $definition = $container->getDefinition($id);
            $alias      = explode('.', $attributes[0]['alias']);

            if (count($alias) > 1) {
                if (!isset($scanners[$alias[0]])) {
                    $scanners[$alias[0]] = array();
                }

                $scanners[$alias[0]][] = $definition;
            } else if (count($alias) === 1) {
                $wrappers[$alias[0]] = $definition;
            }
        }

        foreach ($wrappers as $type => $definition) {
            $definition->addArgument(isset($scanners[$type]) ? $scanners[$type] : array());
        }
    }
}
