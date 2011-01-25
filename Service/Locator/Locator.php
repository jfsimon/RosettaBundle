<?php

namespace Bundle\RosettaBundle\Service\Locator;

class Locator
{
    protected $kernel;
    protected $bundles;

    public function __construct(\AppKernel $kernel)
    {
        $this->kernel = $kernel;
        $this->bundles = $this->getBundleClasses();
    }

    public function guessBundleFromPath($path)
    {
        $path = realpath($path);

        foreach($this->kernel->getBundleDirs() as $root => $dir) {
            $dir = realpath($dir);
            if(substr($path, 0, strlen($dir)) === $dir) {

                foreach($this->getBundleNames($root) as $bundle) {
                    $check = $dir.'/'.$bundle;

                    if(substr($path, 0, strlen($check)) === $check) {
                        return $bundle;
                    }
                }
            }
        }

        die();

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

                if(substr($class, 0, strlen($check)) === $check) {
                    return $bundle;
                }
            }
        }
    }

    public function locateBundle($bundle)
    {
        $bundleName = substr($bundle, strrpos($bundle, '\\') + 1);

        foreach($this->kernel->getBundleDirs() as $root => $dir) {
            if(substr($bundle, 0, strlen($root)) === $root) {
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
}