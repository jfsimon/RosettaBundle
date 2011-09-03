<?php

namespace BeSimple\RosettaBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class TranslatorPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        foreach ($container->findTaggedServiceIds('be_simple_rosetta.translator') as $attributes) {
            $alias     = $attributes[0]['alias'];
            $parameter = 'be_simple_rosetta.translator.'.$alias.'.options';

            if (!$container->hasParameter($parameter)) {
                $container->setParameter($parameter, array());
            }
        }
    }
}
