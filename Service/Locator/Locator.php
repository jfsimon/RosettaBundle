<?php

namespace Bundle\RosettaBundle\Service\Locator;

class Locator
{
    protected $kernel;
    protected $bundles;
    protected $ignore;

    public function __construct(\AppKernel $kernel, array $config)
    {
        $this->kernel = $kernel;
        $this->config = $config;
        $this->bundles = $this->getBundleClasses();
        $this->ignore = is_array($config['ignore']) ? $config['ignore'] : array($config['ignore']);
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

    public function locateBundle($bundle)
    {
        $bundleName = substr($bundle, strrpos($bundle, '\\') + 1);

        foreach($this->kernel->getBundleDirs() as $root => $dir) {
            if($this->startsWith($bundle, $root)) {
                return realpath($dir.'/'.$bundleName);
            }
        }

        return null;
    }

    public function findFiles($root, $masks)
    {
        $finder = new Finder();
        $finder->files();

        foreach($masks as $mask) {
            $finder->name($mask);
        }

        return $finder->in($root);
    }

    protected function getBundleNames($root)
    {
        $bundles = array();

        foreach($this->bundles as $bundle) {
            if(substr($bundle, 0, strlen($root)) === $root) {
                $bundles[] = substr($bundle, strrpos($bundle, '\\') + 1);
            }
        }

        return $bundles;
    }

    protected function getBundleClasses()
    {
        $classes = array();

        foreach($this->kernel->getBundles() as $bundle) {
            $classes[] = get_class($bundle);
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
}