<?php

namespace BeSimple\RosettaBundle\Rosetta;

use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\Util\Filesystem;

/**
 * Translation files backup manager.
 *
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class Backup
{
    /**
     * @var string
     */
    private $directory;

    /**
     * @var string
     */
    private $dateFormat;

    /**
     * Constructor.
     *
     * @param string $directory  Backups directory.
     * @param string $dateFormat Backups date format.
     */
    public function __construct($directory, $dateFormat)
    {
        $this->directory  = $directory;
        $this->dateFormat = $dateFormat;
    }

    /**
     * Creates a backup of given file.
     *
     * @param string $file Path of the file to backup.
     *
     * @return bool True if success.
     */
    public function create($file, \DateTime $datetime = null)
    {
        $datetime  = $datetime ?: new \DateTime();
        $directory = dirname($file).DIRECTORY_SEPARATOR.$this->directory;
        $filename  = $datetime->format($this->dateFormat).'.'.basename($file);

        if (!file_exists($directory)) {
            $fs = new Filesystem();
            $fs->mkdir($directory, 0755);
        }

        return copy($file, $directory.DIRECTORY_SEPARATOR.$filename);
    }

    /**
     * Removes given file backups older than a given datetime.
     *
     * @param string    $file Original file path.
     * @param \DateTime $than Files with creation datetime < given datetime are removed.
     *
     * @return array Array of removed file paths.
     */
    public function removeOlder($file, \DateTime $than)
    {
        $removed = array();

        foreach ($this->retrieve($file) as $backup) {
            $backup   = (string) $backup;
            $prefix   = str_replace('.'.basename($file), '', basename($backup));
            $datetime = \DateTime::createFromFormat($this->dateFormat, $prefix);

            if ($datetime && $datetime < $than) {
                $removed[] = $backup;
                unlink($backup);
            }
        }

        return $removed;
    }

    /**
     * Retrieves all backup for given file.
     *
     * @param string $file Original file path.
     *
     * @return Finder A Finder instance.
     */
    public function retrieve($file)
    {
        $finder = Finder::create()
            ->files()
            ->name('*'.basename($file))
            ->in(dirname($file).DIRECTORY_SEPARATOR.$this->directory)
            ->sortByName()
        ;

        $files = array();
        foreach ($finder as $file) {
            $files[] = (string) $file;
        }

        return $files;
    }
}
