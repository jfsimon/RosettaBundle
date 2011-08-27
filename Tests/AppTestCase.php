<?php

namespace BeSimple\RosettaBundle\Tests;

use Symfony\Component\HttpKernel\Util\Filesystem;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Doctrine\ORM\Tools\SchemaTool;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class AppTestCase extends TestCase
{
    static protected $kernel = null;

    static protected function createKernel($config = 'minimalist', $debug = true)
    {
        $configFile = __DIR__.'/Resources/config/'.$config.'.yml';
        $tempDir    = sys_get_temp_dir().'/be_simple_rosetta_bundle_test';

        $fs = new Filesystem();
        $fs->remove($tempDir);
        $fs->mkdir($tempDir, 0777);

        static::$kernel = new AppKernel($tempDir, $configFile, 'test', $debug);
        static::$kernel->boot();

        return static::$kernel;
    }

    static protected function destroyKernel()
    {
        $fs = new Filesystem();
        $fs->remove(static::$kernel->getTempDir());

        static::$kernel->shutdown();
    }

    static protected function createDatabase()
    {
        $connection    = static::$kernel->getContainer()->get('doctrine')->getConnection();
        $parameters    = $connection->getParams();
        $entityManager = static::$kernel->getContainer()->get('be_simple_rosetta.model.manager');
        $metadata      = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool    = new SchemaTool($entityManager);

        $connection->getSchemaManager()->dropDatabase($parameters['path']);
        $connection->getSchemaManager()->createDatabase($parameters['path']);
        $schemaTool->createSchema($metadata);
    }

    static protected function dropDatabase()
    {
        $connection = static::$kernel->getContainer()->get('doctrine')->getConnection();
        $parameters = $connection->getParams();

        $connection->getSchemaManager()->dropDatabase($parameters['path']);
    }

    static protected function cleanDatabase()
    {
        $entityManager = static::$kernel->getContainer()->get('be_simple_rosetta.model.manager');
        $entities = array(
            'BeSimple\\RosettaBundle\\Entity\\Translation',
            'BeSimple\\RosettaBundle\\Entity\\Message',
            'BeSimple\\RosettaBundle\\Entity\\Group',
        );

        foreach ($entities as $entity) {
            $entityManager->createQuery('delete from '.$entity)->execute();
        }
    }
}
