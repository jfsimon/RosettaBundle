<?php

namespace BeSimple\RosettaBundle\Tests\Translation;

use BeSimple\RosettaBundle\Tests\TestCase;
use BeSimple\RosettaBundle\Entity\Helper;
use BeSimple\RosettaBundle\Translation\Dumper;
use Symfony\Component\Translation\Loader;
use Symfony\Component\HttpKernel\Util\Filesystem;
use Symfony\Component\Yaml\Yaml;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class DumperTest extends TestCase
{
    /**
     * @dataProvider provideDumpData
     */
    public function testDump(array $messages, Dumper\DumperInterface $dumper, Loader\LoaderInterface $loader, $filename)
    {
        $dumper->dump($filename, $messages);
        $this->assertEquals($loader->load($filename, 'en', 'dump')->all('dump'), $messages);

        $fs = new Filesystem();
        $fs->remove(dirname($filename));
    }

    public function testExpand()
    {
        $filename = sys_get_temp_dir().'/be_simple_rosetta_tests/scanner/expanded_keys.yml';
        $dumper   = new Dumper\YamlFileDumper(new Helper());
        $array    = array('a' => array('b' => array('c' => 'd')), 'e');

        $dumper->dump($filename, $array);
        $this->assertEquals(Yaml::parse(file_get_contents($filename)), $array);

        $fs = new Filesystem();
        $fs->remove(dirname($filename));
    }

    public function provideDumpData()
    {
        $helper   = new Helper();
        $messages = array(
            'key1'              => 'value1',
            'key21.key22'       => 'value22',
            'key31.key32.key33' => 'value3',
            'key4'              => '',
            'key5'              => 'value5',
        );

        $formats = array(
            'yaml' => array(
                'dumper' => new Dumper\YamlFileDumper($helper),
                'loader' => new Loader\YamlFileLoader(),
            ),
            'xliff' => array(
                'dumper' => new Dumper\XliffFileDumper($helper, 'en'),
                'loader' => new Loader\XliffFileLoader(),
            ),
            // This test fails & displays the content (because of include?)
            // 'php' => array(
            //     'dumper' => new Dumper\PhpFileDumper($helper),
            //     'loader' => new Loader\PhpFileLoader(),
            // ),
            'csv' => array(
                'dumper' => new Dumper\YamlFileDumper($helper),
                'loader' => new Loader\YamlFileLoader(),
            ),
        );

        $tmpPath = sys_get_temp_dir().'/be_simple_rosetta_tests/scanner';

        $data = array();
        foreach ($formats as $extension => $objects) {
            $data[] = array($messages, $objects['dumper'], $objects['loader'], $tmpPath.'/dump.en.'.$extension);
        }

        return $data;
    }
}
