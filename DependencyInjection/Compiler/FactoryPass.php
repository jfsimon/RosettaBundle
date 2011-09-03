<?php

namespace BeSimple\RosettaBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class FactoryPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('be_simple_rosetta.factory')) {
            return;
        }

        $translators = $container->getParameter('be_simple_rosetta.translator.enabled');

        $container
            ->findDefinition('be_simple_rosetta.factory')
            ->replaceArgument(1, $this->findAliases($container, 'translation.loader'))
            ->replaceArgument(2, $this->findAliases($container, 'be_simple_rosetta.dumper'))
            ->replaceArgument(3, $this->findAliases($container, 'be_simple_rosetta.translator', $translators))
            ->replaceArgument(4, $this->findAliases($container, 'be_simple_rosetta.scanner'))
            ->replaceArgument(5, $this->findAliases($container, 'be_simple_rosetta.parameters_guesser'))
        ;
    }

    /**
     * @param ContainerBuilder $container
     * @param string $tag
     * @return array
     */
    protected function findAliases(ContainerBuilder $container, $tag, array $use = null)
    {
        $aliases = array();

        foreach ($container->findTaggedServiceIds($tag) as $id => $attributes) {
            $alias = $attributes[0]['alias'];

            if (is_null($use) || in_array($alias, $use)) {
                $aliases[$id] = $alias;
            }
        }

        return $aliases;
    }
}
