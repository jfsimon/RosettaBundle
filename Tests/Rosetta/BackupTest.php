<?php

namespace BeSimple\RosettaBundle\Tests\Rosetta;

use BeSimple\RosettaBundle\Tests\AppTestCase;
use Symfony\Component\HttpKernel\Util\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class BackupTest extends AppTestCase
{
    const BACKUP_DIRECTORY   = 'backups';
    const BACKUP_DATE_FORMAT = 'YmdHis';

    private static $backup;

    static public function setUpBeforeClass()
    {
        static::$backup = static::createKernel()
            ->getContainer()
            ->get('be_simple_rosetta.backup')
        ;
    }

    static public function tearDownAfterClass()
    {
        static::destroyKernel();

        $fs = new Filesystem();
        $fs->remove(static::getDirectory());
    }

    /**
     * @dataProvider provideCreationData
     */
    public function testCreation($file, array $dates)
    {
        foreach ($dates as $date) {
            $this->assertTrue(static::$backup->create($file, $date));
        }
    }

    /**
     * @depends testCreation
     * @dataProvider provideListingData
     */
    public function testListing($file, array $expectedFiles)
    {
        $files = static::$backup->retrieve($file);

        $this->assertEquals($expectedFiles, $files);
    }

    /**
     * @depends testListing
     * @dataProvider provideRemovalData
     */
    public function testRemoval($file, \DateTime $dateLimit, array $expectedFiles)
    {
        $files = static::$backup->removeOlder($file, $dateLimit);

        $this->assertEquals($expectedFiles, $files);
    }

    public function provideCreationData()
    {
        $tests = array(
            '1' => array('01', '02', '03'),
            '2' => array('02', '03', '04'),
            '3' => array('03', '04', '05')
        );

        $data = array();
        foreach ($tests as $key => $months) {
            $dates = array();
            foreach ($months as $month) {
                $dates[] = $this->provideDateTime($month);
            }
            $data[] = array($file = $this->provideFilename($key), $dates);
            touch($file);
        }

        return $data;
    }

    public function provideListingData()
    {
        $tests = array(
            '1' => array('01', '02', '03'),
            '2' => array('02', '03', '04'),
            '3' => array('03', '04', '05')
        );

        $data = array();
        foreach ($tests as $key => $months) {
            $data[] = array($this->provideFilename($key), $this->provideExpectedFiles($key, $months));
        }

        return $data;
    }

    public function provideRemovalData()
    {
        $tests = array(
            '1' => array('01', '02'),
            '2' => array('02'),
            '3' => array()
        );

        $limit = $this->provideDateTime('03');

        $data = array();
        foreach ($tests as $key => $months) {
            $data[] = array($this->provideFilename($key), $limit, $this->provideExpectedFiles($key, $months));
        }

        return $data;
    }

    private function provideDateTime($month)
    {
        return \DateTime::createFromFormat('Y-m-d H:i:s', '2011-'.$month.'-01 00:00:00');
    }

    private function provideFilename($key, $month = null)
    {
        $filename  = 'test'.$key;
        $directory = static::getDirectory();

        if ($month) {
            $dateTime  = $this->provideDateTime($month);
            $filename  = $dateTime->format(self::BACKUP_DATE_FORMAT).'.'.$filename;
            $directory = $directory.DIRECTORY_SEPARATOR.self::BACKUP_DIRECTORY;
        }

        return $directory.DIRECTORY_SEPARATOR.$filename;
    }

    private function provideExpectedFiles($key, array $months)
    {
        $expectedFiles = array();

        foreach ($months as $month) {
            $expectedFiles[] = $this->provideFilename($key, $month);
        }

        return $expectedFiles;
    }

    static private function getDirectory()
    {
        $directory = sys_get_temp_dir().DIRECTORY_SEPARATOR.'be_simple_rosetta_backup_test';

        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        return $directory;
    }
}
