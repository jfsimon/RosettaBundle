<?php

namespace BeSimple\RosettaBundle\Rosetta;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Translation\Loader\LoaderInterface;
use BeSimple\RosettaBundle\Translation\Dumper\DumperInterface;
use BeSimple\RosettaBundle\Translation\Scanner\ScannerInterface;
use BeSimple\RosettaBundle\Translation\Webservice\TranslatorInterface;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class Factory
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var array
     */
    private $loaders;

    /**
     * @var array
     */
    private $dumpers;

    /**
     * @var array
     */
    private $translators;

    /**
     * @var array
     */
    private $scanners;

    /**
     * @var array
     */
    private $parametersGuessers;

    /**
     * @var array
     */
    private $defaults;

    /**
     * Constructor.
     *
     * @param Container $container          The service container
     * @param array     $loaders            Loader services aliases
     * @param array     $dumpers            Dumper services aliases
     * @param array     $translators        Translator services aliases
     * @param array     $scanners           Scanner services aliases
     * @param array     $parametersGuessers Parameters guesser services aliases
     * @param array     $defaults           Default values
     */
    public function __construct(Container $container, array $loaders, array $dumpers, array $translators, array $scanners, array $parametersGuessers, array $defaults)
    {
        $this->container          = $container;
        $this->loaders            = $loaders;
        $this->dumpers            = $dumpers;
        $this->translators        = $translators;
        $this->scanners           = $scanners;
        $this->parametersGuessers = $parametersGuessers;
        $this->defaults           = $defaults;
    }

    /**
     * Returns loader service by alias.
     *
     * @param string $alias Loader alias
     *
     * @return LoaderInterface Loader service
     */
    public function getLoader($alias)
    {
        return $this->find($alias, $this->loaders);
    }

    /**
     * Returns dumper service by alias.
     *
     * @param string|null $alias Dumper alias
     *
     * @return DumperInterface Dumper service
     */
    public function getDumper($alias = null)
    {
        return $this->find($alias ?: $this->defaults['dumper'], $this->dumpers);
    }

    /**
     * Returns translator service by alias.
     *
     * @param string|null $alias Translator alias
     *
     * @return TranslatorInterface Translator service
     */
    public function getTranslator($alias = null)
    {
        return $this->find($alias ?: $this->defaults['translator'], $this->translators);
    }

    /**
     * Returns translator aliases.
     *
     * @return array Translator aliases
     */
    public function getTranslatorAliases()
    {
        return array_values($this->translators);
    }

    /**
     * Returns scanner service by alias.
     *
     * @param string|null $alias Scanner alias
     *
     * @return ScannerInterface Scanner service
     */
    public function getScanner($alias = null)
    {
        return $this->find($alias, $this->scanners);
    }

    /**
     * Returns scanner aliases.
     *
     * @return array Scanner aliases
     */
    public function getScannerAliases()
    {
        return array_values($this->scanners);
    }

    /**
     * Returns parameters guesser service by alias.
     *
     * @param string $alias Parameters guesser alias
     *
     * @return ScannerInterface Parameters guesser service
     */
    public function getParametersGuesser($alias = 'wrapper')
    {
        return $this->find($alias, $this->parametersGuessers);
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @param string $alias
     * @param array $classes
     *
     * @return mixed
     */
    private function find($alias, array $aliases)
    {
        if (false === $service = array_search($alias, $aliases)) {
            throw new \InvalidArgumentException('Alias "'.$alias.'" not found, valid choices are "'.implode('", "', $aliases).'"');
        }

        return $this->container->get($service);
    }
}
