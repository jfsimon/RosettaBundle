<?php

namespace BeSimple\RosettaBundle\Tests\Rosetta;

use BeSimple\RosettaBundle\Tests\AppTestCase;
use BeSimple\RosettaBundle\Rosetta\Locator;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class LocatorTest extends AppTestCase
{
    protected $bundles;
    protected $locator;

    public function setUp()
    {
        static::createKernel();

        $this->bundles = array('FrameworkBundle', 'TwigBundle');
        $this->locator = new Locator(static::$kernel, $this->bundles, true, false);
    }

    public function tearDown()
    {
        static::destroyKernel();
    }

    public function testProcessedPaths()
    {
        $expected = array();
        foreach ($this->bundles as $bundle) {
            $expected[] = implode(DIRECTORY_SEPARATOR, array(static::$projectRoot, 'vendor', 'symfony', 'src', 'Symfony', 'Bundle', $bundle));
        }

        $this->assertEquals($expected, $this->locator->getProcessedPaths());
    }

    /**
     * @dataProvider provideBundlesFile
     */
    public function testInScope($file, $bundle)
    {
        $file = static::$projectRoot.DIRECTORY_SEPARATOR.$file;
        $this->assertEquals(in_array($bundle, $this->bundles), $this->locator->inScope($file));
    }

    /**
     * @dataProvider provideBundlesFile
     */
    public function testBundleName($file, $bundle)
    {
        $file = static::$projectRoot.DIRECTORY_SEPARATOR.$file;
        $this->assertEquals($bundle, $this->locator->guessBundleName($file));
    }

    public function provideBundlesFile()
    {
        $bundleDir = implode(DIRECTORY_SEPARATOR, array('vendor', 'symfony', 'src', 'Symfony', 'Bundle'));

        return array(
            array(implode(DIRECTORY_SEPARATOR, array($bundleDir, 'FrameworkBundle', 'Client.php')), 'FrameworkBundle'),
            array(implode(DIRECTORY_SEPARATOR, array($bundleDir, 'TwigBundle', 'TwigEngine.php')), 'TwigBundle'),
            array(implode(DIRECTORY_SEPARATOR, array($bundleDir, 'DoctrineBundle', 'Registry.php')), 'DoctrineBundle'),
            array(implode(DIRECTORY_SEPARATOR, array(static::$projectRoot, 'web', 'app.php')), null),
        );
    }
}
