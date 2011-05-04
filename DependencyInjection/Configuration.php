<?php

namespace BeSimple\RosettaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\ScalarNodeDefinition;

class Configuration
{
    private $kernelDebug;

    /**
     * Generates the configuration tree.
     *
     * @param Boolean $kernelDebug
     *
     * @return \Symfony\Component\Config\Definition\ArrayNode The config tree
     */
    public function getConfigTree($kernelDebug)
    {
        $this->kernelDebug = (bool)$kernelDebug;

        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('besimple_rosetta');

        $this->addGlobalNodes($rootNode);
        $this->addLocalesSection($rootNode);
        $this->addTasksSection($rootNode);
        $this->addRatingSection($rootNode);
        $this->addScannerSection($rootNode);

        return $treeBuilder->buildTree();
    }

    private function addGlobalNodes(ArrayNodeDefinition $rootNode)
    {
        $rootNode
                ->children()
                ->scalarNode('database')->cannotBeEmpty()->isRequired()->end()
                ->booleanNode('live')->defaultFalse()->end()
                ->scalarNode('translator')->defaultValue('google')->end()
                ->scalarNode('deployer')->defaultValue('yaml')->end()
                ->end();
    }

    private function addLocalesSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
                ->children()
                ->scalarNode('input')->defaultValue('%session.default_locale%')->end()
                ->scalarNode('output')
                ->isRequired()
                ->cannotBeEmpty()
                ->beforeNormalization()
                ->ifString()
                ->then(function($v)
        {
            return array($v);
        })
                ->end()
                ->end()
                ->end();
    }

    private function addTasksSection(ArrayNodeDefinition $rootNode)
    {
        $tasksNode = $rootNode->children()->arrayNode('tasks');

        $tasksNode->append($this->getTasksNode('default_tasks', true));
        $tasksNode->append($this->getTasksNode('scanner_tasks'));
        $tasksNode->append($this->getTasksNode('importer_tasks'));
        $tasksNode->append($this->getTasksNode('live_tasks'));
    }

    private function addRatingSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
                ->children()
                ->arrayNode('rating')
                ->children()
                ->scalarNode('translator')->defaultValue(1)->end()
                ->scalarNode('importer')->defaultValue(5)->end()
                ->end()
                ->end()
                ->end();
    }

    private function addScannerSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
                ->children()
                ->scalarNode('scanners')
                ->isRequired()
                ->cannotBeEmpty()
                ->beforeNormalization()
                ->ifString()
                ->then(function($v)
        {
            return array($v);
        })
                ->end()
                ->defaultValue(array('php', 'twig', 'validators', 'forms'))
                ->end()
                ->end();
    }

    private function getTasksNode($name, $default = false)
    {
        $node = new ScalarNodeDefinition($name);

        if ($default) {
            $node->isRequired()->defaultValue(array('translate', 'choose', 'store'));
        }

        $node
                ->cannotBeEmpty()
                ->beforeNormalization()
                ->ifString()
                ->then(function($v)
        {
            return array($v);
        })
                ->end();

        return $node;
    }
}
