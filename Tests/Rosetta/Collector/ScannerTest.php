<?php

namespace BeSimple\RosettaBundle\Tests\Rosetta\Workflow;

use BeSimple\RosettaBundle\Tests\AppTestCase;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class ScannerTest extends AppTestCase
{
    /**
     * @dataProvider provideScanBundleData
     */
    public function testScanBundle($bundle, $domain)
    {
        $inputs = static::createKernel()
            ->getContainer()
            ->get('be_simple_rosetta.scanner')
            ->scanBundle($bundle)
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
     * @dataProvider provideScanBundleData
     */
    public function testScanBundleWithDomain($bundle, $domain)
    {
        $inputs = static::createKernel()
            ->getContainer()
            ->get('be_simple_rosetta.scanner')
            ->scanBundle($bundle, $domain)
            ->fetchInputs()
        ;

        $this->assertTrue($inputs->count() > 0);

        static::destroyKernel();
    }

    public function provideScanBundleData()
    {
        return array(
            array('BeSimpleRosettaBundle', 'messages'),
            array('BeSimpleRosettaBundle', 'tests'),
        );
    }
}
