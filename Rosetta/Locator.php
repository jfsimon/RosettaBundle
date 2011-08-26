<?php

namespace BeSimple\RosettaBundle\Rosetta;

use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class Locator
{
    // As each message domain shall be bound to a bundle name,
    // this fake one is used for main app translations.
    const APP_BUNDLE_NAME = 'App';

    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var array
     */
    private $bundles;

    /**
     * @var bool
     */
    private $appDir;

    /**
     * @var bool
     */
    private $srcDir;

    /**
     * @var string
     */
    private $srcPath;

    /**
     * @var array|null
     */
    private $bundlePaths;

    /**
     * @var array|null
     */
    private $processedPaths;

    /**
     * @var array|null
     */
    private $processedBundles;

    /**
     * Constructor.
     *
     * @param KernelInterface $kernel  A kernel instance
     * @param array           $bundles An array of bundle names
     * @param bool            $appDir  Is app dir in the scope
     * @param bool            $srcDir  Is src dir in the scope
     */
    public function __construct(KernelInterface $kernel, array $bundles, $appDir, $srcDir)
    {
        $this->kernel  = $kernel;
        $this->bundles = $bundles;
        $this->appDir  = (boolean) $appDir;
        $this->srcDir  = (boolean) $srcDir;
        $this->srcPath = realpath($kernel->getRootDir().DIRECTORY_SEPARATOR.'..');

        // internal cache
        $this->bundlePaths       = null;
        $this->processedPaths    = null;
        $this->preocessedBundles = null;
    }

    /**
     * Returns the kernel class.
     *
     * @return KernelInterface A kernel instance
     */
    public function getKernel()
    {
        return $this->kernel;
    }

    /**
     * Returns the bundle paths.
     *
     * @return array An array of paths
     */
    public function getBundlePaths()
    {
        if (!is_array($this->bundlePaths)) {
            $this->bundlePaths = array();

            foreach ($this->kernel->getBundles() as $bundle) {
                $this->bundlePaths[$bundle->getName()] = $bundle->getPath();
            }

            $this->bundlePaths[self::APP_BUNDLE_NAME] = $this->kernel->getRootDir();
        }

        return $this->bundlePaths;
    }

    /**
     * Return path for given bundle, null if not found.
     *
     * @param string $bundle A bundle name
     *
     * @return string|null Bundle path
     */
    public function getBundlePath($bundle)
    {
        $paths = $this->getBundlePaths();

        return isset($paths[$bundle]) ? $paths[$bundle] : null;
    }

    /**
     * Returns all bundles in the scope.
     *
     * @return array An array of bundle names
     */
    public function getProcessedBundles()
    {
        if (!is_array($this->processedBundles)) {
            $this->processedBundles = $this->bundles;

            if ($this->srcDir) {
                $this->processedBundles = array_merge(
                    $this->processedBundles,
                    $this->getSrcBundles()
                );
            }

            $this->processedBundles = array_unique($this->processedBundles);
        }

        return $this->processedBundles;
    }

    /**
     * Returns all bundles under src dir.
     *
     * @return array An array of bundle names
     */
    public function getSrcBundles()
    {
        $bundles = array();

        foreach ($this->bundles as $bundle) {
            $bundlePath = $this->getBundlePath($bundle);

            if (0 !== strstr($bundlePath, $this->srcPath)) {
                $bundles[] = $bundle;
            }
        }

        return $bundles;
    }

    /**
     * Returns all bundle paths in the scope.
     *
     * @return array An array of paths
     */
    public function getProcessedPaths()
    {
        if (!is_array($this->processedPaths)) {
            $this->processedPaths = array();

            foreach ($this->processedBundles as $bundle) {
                $this->processedPaths[] = $this->getBundlePath($bundle);
            }
        }

        return $this->processedPaths;
    }

    /**
     * Returns true if given file is in the scope.
     *
     * @param string $file A filename
     *
     * @return bool Is file in the scope
     */
    public function inScope($file) {
        foreach ($this->getProcessedPaths() as $path) {
            if (0 === strstr($file, $path)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns bundle name containing given file.
     *
     * @param string $file A filename
     *
     * @return null|string A bundle name or null
     */
    public function guessBundleName($file)
    {
        foreach ($this->getBundlePaths() as $bundle => $path) {
            if (0 === strstr($file, $path)) {
                return $bundle;
            }
        }

        return null;
    }
}
