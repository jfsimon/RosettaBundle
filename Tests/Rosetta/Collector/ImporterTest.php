<?php

namespace BeSimple\RosettaBundle\Tests\Rosetta\Workflow;

use BeSimple\RosettaBundle\Tests\AppTestCase;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class ImporterTest extends AppTestCase
{
    /**
     * @dataProvider provideImportBundleData
     */
    public function testImportBundle($bundle, $domain)
    {
        $inputs = static::createKernel()
            ->getContainer()
            ->get('be_simple_rosetta.importer')
            ->importBundle($bundle)
            ->fetchInputs()
        ;

        $found = false;
        foreach ($inputs->all() as $input) {
            if ($input->getDomain() === $domain) {
                $found = true;
                break;
            }
        }

        $this->assertTrue($found);

        static::destroyKernel();
    }

    /**
     * @dataProvider provideImportBundleData
     */
    public function testImportBundleWithDomain($bundle, $domain)
    {
        $inputs = static::createKernel()
            ->getContainer()
            ->get('be_simple_rosetta.importer')
            ->importBundle($bundle, $domain)
            ->fetchInputs()
        ;

        $this->assertTrue($inputs->count() > 0);

        static::destroyKernel();
    }

    public function provideImportBundleData()
    {
        return array(
            array('FrameworkBundle', 'validators'),
        );
    }
}
