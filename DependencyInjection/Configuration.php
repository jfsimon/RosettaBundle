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
     * @var array
     */
    private $defaultLoaders;

    /**
     * @var string
     */
    private $defaultDumper;

    /**
     * @var string
     */
    private $defaultTranslator;

    /**
     * @var array
     */
    private $availableImporterActions;

    /**
     * @var array
     */
    private $defaultParametersGuessers;

    /**
     * @var array
     */
    private $defaultManageValue;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->arrayWrapper = function($value) {
            return array($value);
        };

        $this->defaultLoaders            = array('xliff', 'yml', 'php', 'csv');
        $this->defaultDumper             = 'xliff';
        $this->defaultTranslator         = 'google';
        $this->availableImporterActions  = array('keep', 'backup', 'remove');
        $this->defaultParametersGuessers = array('{*}', '{{*}}'); //, '%*%');
        $this->defaultManageValue        = array('app', 'src');
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
        $this->addManageSection($rootNode);

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
                        ->scalarNode('adapter')->defaultValue($this->defaultTranslator)->end()
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
                        ->scalarNode('format')->defaultValue($this->defaultDumper)->end()
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
                            ->defaultValue($this->defaultLoaders)
                            ->beforeNormalization()
                                ->ifString()->then($this->arrayWrapper)
                            ->end()
                            ->prototype('scalar')->end()
                        ->end()
                        ->arrayNode('parameters')
                            ->defaultValue($this->defaultParametersGuessers)
                            ->beforeNormalization()
                                ->ifString()->then($this->arrayWrapper)
                            ->end()
                            ->prototype('scalar')->end()
                        ->end()
                        ->scalarNode('then')
                            ->validate()
                                ->ifNotInArray($this->availableImporterActions)
                                ->thenInvalid('Importer.then valid options are "'.implode('", "', $this->availableImporterActions).'"; "%s" found instead.')
                            ->end()
                            ->defaultValue('backup')
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
    protected function addManageSection(ArrayNodeDefinition $rootNode)
    {
        $that = $this;
        $setDefaults = function() use ($that) {
            return $that->defaultManageValue;
        };

        $rootNode
            ->children()
                ->arrayNode('manage')
                    ->addDefaultsIfNotSet()
                    ->beforeNormalization()
                        ->ifNull()->then($setDefaults)
                        ->ifTrue()->then($setDefaults)
                        ->ifArray()->then(function ($values) {
                            $manage = array();
                            foreach ($values as $value) {
                                if ('app' === $value) {
                                    $manage['app_dir'] = true;
                                } else if ('src' === $value) {
                                    $manage['src_dir'] = true;
                                } else {
                                    if (!isset($manage['bundles'])) {
                                        $manage['bundles'] = array();
                                    }
                                    $manage['bundles'][] = $value;
                                }
                            }
                            return $manage;
                        })
                        ->ifString()->then(function($value) {
                            if ('app' === $value) {
                                return array('app_dir' => true);
                            } else if ('src' === $value) {
                                return array('src_dir' => true);
                            } else {
                                return array('bundles' => $value);
                            }
                        })
                    ->end()
                    ->children()
                        ->arrayNode('bundles')
                            ->defaultValue(array())
                            ->beforeNormalization()
                                ->ifString()->then($this->arrayWrapper)
                            ->end()
                            ->prototype('scalar')->end()
                        ->end()
                        ->booleanNode('app_dir')->defaultFalse()->end()
                        ->booleanNode('src_dir')->defaultFalse()->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
