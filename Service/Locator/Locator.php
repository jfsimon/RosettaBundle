<?php

namespace Bundle\RosettaBundle\Service\Locator;

use Symfony\Component\Finder\Finder;

class Locator
{
    protected $kernel;
    protected $ignore;
    protected $bundleClasses;
    protected $bundlePathes;

    public function __construct(\AppKernel $kernel, array $config)
    {
        $this->kernel = $kernel;
        $this->ignore = $config['ignore'] ? (is_array($config['ignore']) ? $config['ignore'] : array($config['ignore'])) : array();
        $this->$bundleClasses = $this->getBundleClasses();
    }

    public function guessBundleFromPath($path)
    {
        $path = realpath($path);

        foreach($this->kernel->getBundleDirs() as $root => $dir) {
            $dir = realpath($dir);
            if($this->startsWith($path, $dir)) {

                foreach($this->getBundleNames($root) as $bundle) {
                    $check = $dir.'/'.$bundle;

                    if(substr($path, 0, strlen($check)) === $check) {
                        return $bundle;
                    }
                }
            }
        }

        return null;
    }

    public function guessBundleFromClass($class)
    {
        if(is_object($class)) {
            $class = get_class($class);
        }

        foreach(array_keys($this->kernel->getBundleDirs()) as $root) {
            foreach($this->getBundleNames($root) as $bundle) {
                $check = $root.'\\'.$bundle;

                if($this->startsWith($class, $check)) {
                    return $bundle;
                }
            }
        }
    }

    public function locateBundle($bundleName)
    {
        if(strpos($bundleName, '\\') !== false) {
            $bundleName = substr($bundleName, strrpos($bundleName, '\\') + 1);
        }

        if(! isset($this->bundlePathes[$bundleName])) {
            $reflection = new \ReflectionClass($this->kernel->getBundle($bundleName));
            $this->bundlePathes[$bundleName] = $reflection->getFileName();
        }

        return $this->bundlePathes[$bundleName];
    }

    public function locateTranslationFiles($bundleName)
    {
        $dir = $this->locateBundle($bundleName).'/Resources/translations';

        $finder = new Finder();
        return $finder->files()->in($dir);
    }

    public function locateTemplates($bundleName)
    {
        $dir = $this->locateBundle($bundleName).'/Resources/views';

        $finder = new Finder();
        return $finder->files()->in($dir);
    }

    public function locateClasses($bundleName)
    {
        $dir = $this->locateBundle($bundleName);

        $finder = new Finder();
        return $finder
            ->files()->name('*.php')
            ->exclude($dir.'/Resources')->in($dir);
    }

    public function findFiles($dir, array $masks=array())
    {
        $finder = new Finder();
        $finder->files();

        foreach($masks as $mask) {
            $finder->name($mask);
        }

        return $finder->in($dir);
    }

    public function getBundleNames($namespace=null)
    {
        $names = array();

        foreach($this->bundleClasses as $className) {
            if($namespace && ! $this->startsWith($className, $namespace)) {
                continue;
            }

            $names[] = substr($className, strrpos($className, '\\') + 1);
        }

        return $names;
    }

    protected function getBundleClasses()
    {
        $classes = array();

        foreach($this->kernel->getBundles() as $bundle) {
            $classname = get_class($bundle);

            if(! $this->ignoreBundle($classname)) {
                $classes[] = $classname;
            }
        }

        return $classes;
    }

    protected function startsWith($string, $start)
    {
        return substr($string, 0, strlen($start)) === $start;
    }

    protected function endsWith($string, $end)
    {
        return substr($string, strlen($string) - strlen($end)) === $end;
    }

    protected function ignoreBundle($className)
    {
        foreach($this->config['ignore'] as $ignore) {
            if($this->startsWith($className, $ignore)) {
                return true;
            }
        }

        return false;
    }
}