<?php

namespace BeSimple\RosettaBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class TasksPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('be_simple_rosetta.tasks')) {
            return;
        }

        $tasks = $container->getDefinition('be_simple_rosetta.tasks');

        foreach ($container->findTaggedServiceIds('be_simple_rosetta.task') as $id => $attributes) {
            $definition = $container->getDefinition($id);
            $alias      = $attributes[0]['alias'];

            $tasks->addMethodCall('add', array($alias, $definition));
        }
    }
}
