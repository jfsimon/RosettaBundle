<?php

namespace Bundle\RosettaBundle\Tests;

require_once __DIR__.'/../../../../app/AppKernel.php';

use AppKernel;
use Bundle\RosettaBundle\Service\Locator\Locator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class RosettaTestCase extends WebTestCase
{
    protected function getTestFile($file)
    {
        return realpath(__DIR__.'/../Resources/tests').'/'.$file;
    }

    protected function buildLocator()
    {
        $kernel = new AppKernel('test', false);
        $kernel->boot();

        $locator = new Locator($kernel,array('ignore' => array('Symfony\\Bundle')));

        return $locator;
    }

    protected function resetDatabase()
    {
        $directory = __DIR__.'/../../../..';
        $commands = array(
            './app/console doctrine:database:drop',
            './app/console doctrine:database:create',
            './app/console doctrine:schema:create',
        );

        foreach ($commands as $command) {
            $process = new Process($command, $directory);
            $process->run();
        }
    }
}