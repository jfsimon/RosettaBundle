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
     * @var string
     */
    private $defaultBackupDirectory;

    /**
     * @var string
     */
    private $defaultBackupDateFormat;

    /**
     * @var string
     */
    private $defaultModelHelper;

    /**
     * @var int
     */
    private $defaultBatchLimit;

    /**
     * @var int
     */
    private $defaultMinRating;

    /**
     * @var array
     */
    private $defaultTasks;

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
        $this->defaultBackupDirectory    = 'backups';
        $this->defaultBackupDateFormat   = 'YmdHis';
        $this->defaultModelHelper        = 'BeSimple\\RosettaBundle\\Entity\\Helper';
        $this->defaultBatchLimit         = 50;
        $this->defaultMinRating          = 0;
        $this->defaultTasks              = array('translate', 'select', 'dump');
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
        $this->addBackupSection($rootNode);
        $this->addModelSection($rootNode);
        $this->addWorkflowSection($rootNode);
        $this->addLocalesSection($rootNode);

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
                    ->useAttributeAsKey('key')
                    ->prototype('array')
                        ->useAttributeAsKey('key')
                        ->beforeNormalization()
                            ->ifNull()->thenEmptyArray()
                            ->ifTrue()->thenEmptyArray()
                            ->ifString()->then(function ($value) {
                                return array('api_key' => $value);
                            })
                        ->end()
                        ->prototype('scalar')->end()
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
                        ->booleanNode('backup')->defaultFalse()->end()
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
                        ->arrayNode('tasks')
                            ->defaultNull()
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
                        ->ifArray()->then(function ($values) {
                            $manage = array('app_dir' => false, 'src_dir' => false);
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
                    ->end()
                    ->beforeNormalization()
                        ->ifNull()->then($setDefaults)
                        ->ifTrue()->then($setDefaults)
                        ->ifString()->then(function($value) {
                            if ('app' === $value) {
                                return array('app_dir' => true, 'src_dir' => false);
                            } else if ('src' === $value) {
                                return array('app_dir' => false, 'src_dir' => true);
                            } else {
                                return array('app_dir' => false, 'src_dir' => false, 'bundles' => $value);
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
                        ->booleanNode('app_dir')->defaultTrue()->end()
                        ->booleanNode('src_dir')->defaultTrue()->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * @param ArrayNodeDefinition $rootNode
     */
    private function addBackupSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('backup')
                    ->addDefaultsIfNotSet()
                    ->beforeNormalization()
                        ->ifNull()->thenEmptyArray()
                        ->ifTrue()->thenEmptyArray()
                        ->ifString()->then(function($value) {
                            return 'disabled' === $value
                                ? array('enabled' => false)
                                : array('directory' => $value);
                        })
                    ->end()
                    ->children()
                        ->scalarNode('directory')->defaultValue($this->defaultBackupDirectory)->end()
                        ->scalarNode('date_format')->defaultValue($this->defaultBackupDateFormat)->end()
                        ->booleanNode('enabled')->defaultTrue()->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * @param ArrayNodeDefinition $rootNode
     */
    private function addModelSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('model')
                    ->addDefaultsIfNotSet()
                    ->beforeNormalization()
                        ->ifNull()->thenEmptyArray()
                        ->ifTrue()->thenEmptyArray()
                        ->ifString()->then(function($value) {
                            return array('manager' => $value);
                        })
                    ->end()
                    ->children()
                        ->scalarNode('manager')->defaultNull()->end()
                        ->booleanNode('helper')->defaultValue($this->defaultModelHelper)->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * @param ArrayNodeDefinition $rootNode
     */
    private function addWorkflowSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('workflow')
                    ->addDefaultsIfNotSet()
                    ->beforeNormalization()
                        ->ifNull()->thenEmptyArray()
                        ->ifTrue()->thenEmptyArray()
                    ->end()
                    ->children()
                        ->scalarNode('batch_limit')->defaultValue($this->defaultBatchLimit)->end()
                        ->scalarNode('min_rating')->defaultValue($this->defaultMinRating)->end()
                        ->arrayNode('tasks')
                            ->defaultValue($this->defaultTasks)
                            ->prototype('scalar')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * @param ArrayNodeDefinition $rootNode
     */
    protected function addLocalesSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('locales')
                    ->addDefaultsIfNotSet()
                    ->beforeNormalization()
                        ->ifArray()->then(function($values) {
                            return range(0, count($values) - 1) === array_keys($values)
                                ? array('translations' => $values)
                                : $values;
                        })
                        ->ifNull()->thenEmptyArray()
                        ->ifTrue()->thenEmptyArray()
                        ->ifString()->then(function($value) {
                            return array('translations' => array($value));
                        })
                    ->end()
                    ->children()
                        ->scalarNode('source')->defaultValue('%session.default_locale%')->end()
                        ->arrayNode('translations')
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
