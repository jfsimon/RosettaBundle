<?php

namespace BeSimple\RosettaBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Component\HttpKernel\Util\Filesystem;
use Symfony\Component\DomCrawler\Crawler;
use BeSimple\RosettaBundle\Tests\AppKernel;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
abstract class WebTestCase extends BaseWebTestCase
{
    static protected $tmpDirectory = null;
    static protected $configFile   = null;

    static protected function createClient($configName)
    {
        $options = array('environment' => 'test', 'debug' => true, 'config' => $configName);
        $server  = array();

        return parent::createClient($options, $server);
    }

    static protected function createKernel(array $options)
    {
        static::$tmpDirectory = sys_get_temp_dir().'/be_simple_rosetta_bundle_test';
        static::$configFile   = __DIR__.'/Resources/config/'.$options['config'].'.yml';

        $fs = new Filesystem();
        $fs->remove(static::$tmpDirectory);
        $fs->mkdir(static::$tmpDirectory, 0777);

        return new AppKernel(static::$tmpDirectory, static::$configFile, $options['environment'], $options['debug']);
    }

    protected function tearDown()
    {
        if (null !== static::$kernel) {
            static::$kernel->shutdown();
        }

        $fs = new Filesystem();
        $fs->remove(static::$tmpDirectory);
    }
}
