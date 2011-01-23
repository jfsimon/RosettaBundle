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

    public function guessPathBundle($path)
    {
        foreach($this->kernel->getBundleNames() as $root => $dir) {
            if(substr($path, strlen($root)) === $root) {
                foreach($this->getBundleNames($root) as $bundle) {
                    $check = $root.'/'.$bundle;

                    if(substr($path, strlen($check)) === $check) {
                        return $bundle;
                    }
                }
            }
        }

        return null;
    }

    public function guessClassBundle($class)
    {
        if(is_object($class)) {
            $class = get_class($class);
        }

        foreach(array_keys($this->kernel->getBundleDirs()) as $root) {
            foreach($this->getBundleNames($root) as $bundle) {
                $check = $root.'\\'.$bundle;

                if(substr($class, strlen($check)) === $check) {
                    return $bundle;
                }
            }
        }
    }

    public function locateBundle($bundle)
    {
        $bundleName = substr($bundle, strrpos('\\', $bundle) + 1);

        foreach($this->kernel->getBundleDirs() as $root => $dir) {
            if(substr($bundle, strlen($root)) === $root) {
                return $dir.'/'.$bundleName;
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
            if(substr($bundle, strlen($root)) === $root) {
                $bundles[] = substr($bundle, strrpos('\\', $bundle) + 1);
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