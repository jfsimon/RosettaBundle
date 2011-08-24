<?php

namespace BeSimple\RosettaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @var \Closure
     */
    private $arrayWrapper;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->arrayWrapper = function($value) {
            return array($value);
        };
    }

    /**
     * Creates configuration tree builder.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('be_simple_rosetta');

        $this->addTranslatorSection($rootNode);
        $this->addDumperSection($rootNode);
        $this->addImporterSection($rootNode);

        return $treeBuilder;
    }

    /**
     * @param ArrayNodeDefinition $rootNode
     */
    private function addTranslatorSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('translator')
                    ->addDefaultsIfNotSet()
                    ->beforeNormalization()
                        ->ifNull()->thenEmptyArray()
                        ->ifTrue()->thenEmptyArray()
                        ->ifString()->then(function($value) {
                            return 'disabled' === $value
                                ? array('enabled' => false)
                                : array('adapter' => $value);
                        })
                    ->end()
                    ->children()
                        ->scalarNode('adapter')->defaultValue('google')->end()
                        ->arrayNode('options')
                            ->defaultValue(array())
                            ->prototype('scalar')->end()
                        ->end()
                        ->booleanNode('enabled')->defaultTrue()->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * @param ArrayNodeDefinition $rootNode
     */
    private function addDumperSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('dumper')
                    ->addDefaultsIfNotSet()
                    ->beforeNormalization()
                        ->ifNull()->thenEmptyArray()
                        ->ifTrue()->thenEmptyArray()
                        ->ifString()->then(function ($value) {
                            return 'disabled' === $value
                                ? array('enabled' => false)
                                : array('format' => $value);
                        })
                    ->end()
                    ->children()
                        ->scalarNode('format')->defaultValue('xliff')->end()
                        ->booleanNode('no_merge')->defaultFalse()->end()
                        ->booleanNode('enabled')->defaultTrue()->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * @param ArrayNodeDefinition $rootNode
     */
    private function addImporterSection(ArrayNodeDefinition $rootNode)
    {
        $defaultLoaders    = array('xliff', 'yml', 'php', 'csv');
        $defaultParameters = array('{*}', '{{*}}'); //, '%*%');

        $rootNode
            ->children()
                ->arrayNode('importer')
                    ->addDefaultsIfNotSet()
                    ->beforeNormalization()
                        ->ifNull()->thenEmptyArray()
                        ->ifTrue()->thenEmptyArray()
                        ->ifString()->then(function($value) {
                            return 'disabled' === $value
                                ? array('enabled' => false)
                                : array('then' => $value);
                        })
                    ->end()
                    ->children()
                        ->arrayNode('formats')
                            ->defaultValue($defaultLoaders)
                            ->beforeNormalization()
                                ->ifString()->then($this->arrayWrapper)
                            ->end()
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('parameters')
                            ->defaultValue($defaultParameters)
                            ->beforeNormalization()
                                ->ifString()->then($this->arrayWrapper)
                            ->end()
                            ->prototype('scalar')->end()
                        ->end()
                        ->scalarNode('then')->defaultValue('backup')->end()
                        ->booleanNode('enabled')->defaultTrue()->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
