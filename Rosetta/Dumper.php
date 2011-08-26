<?php

namespace BeSimple\RosettaBundle\Rosetta;

use BeSimple\RosettaBundle\Model\Message;
use BeSimple\RosettaBundle\Model\Translation;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class Dumper
{
    /**
     * @var Factory
     */
    private $factory;

    /**
     * @var Locator
     */
    private $locator;

    /**
     * @var Backup
     */
    private $backup;

    /**
     * @var array
     */
    private $defaultFormat;

    /**
     * @var bool
     */
    private $backupFiles;

    /**
     * @var array
     */
    private $messages;

    /**
     * Constructor.
     *
     * @param Factory $factory       A Factory instance
     * @param Locator $locator       A Locator instance
     * @param Backup  $backup        A Backup instance
     * @param array   $defaultFormat Default file format
     * @param array   $backupFiles   Backup existing files
     */
    public function __construct(Factory $factory, Locator $locator, Backup $backup, $defaultFormat, $backupFiles)
    {
        $this->factory        = $factory;
        $this->locator        = $locator;
        $this->backup         = $backup;
        $this->defaultFormat  = $defaultFormat;
        $this->backupFiles    = $backupFiles;
        $this->messages       = array();
    }

    /**
     * Adds a message.
     *
     * @param Message $message A Message instance
     *
     * @return Dumper This instance
     */
    public function add(Message $message)
    {
        $prefix = $this->locator->getBundlePath($message->getGroup()->getBundle())
            .DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'translations'
            .DIRECTORY_SEPARATOR.$message->getGroup()->getDomain().'.';

        $filter = function(Translation $translation) {
            return $translation->getIsSelected();
        };

        foreach ($message->getTranslations()->filter($filter) as $translation) {
            $key = $prefix.$translation->getLocale();

            if (!isset($this->messages[$key])) {
                $this->messages[$key] = array();
            }

            $this->messages[$key][$message->getText()] = $translation->getText();
        }

        return $this;
    }

    /**
     * Dumps stored messages.
     *
     * @param string|null $format File format
     * @param bool        $merge  Merge translations
     *
     * @return Dumper This instance
     */
    public function dump($format = null, $merge = false)
    {
        $format = $format ?: $this->defaultFormat;
        $dumper = $this->factory->getDumper($format);

        foreach ($this->messages as $key => $messages) {
            $file = $key.'.'.$format;

            if (file_exists($file)) {
                if ($this->backupFiles) {
                    $this->backup->create($file);
                }

                if ($merge) {
                    $messages = array_merge($this->load($file, $format), $messages);
                }
            }

            $dumper->dump($file, $messages);
        }

        $this->messages = array();

        return $this;
    }

    /**
     * Dumps stored message forcing merge.
     *
     * @param string|null $format File format
     *
     * @return Dumper This instance
     */
    public function merge($format = null)
    {
        return $this->dump($format, true);
    }

    /**
     * Loads translations.
     *
     * @param string $file Translations file
     *
     * @return array An array of translations
     */
    private function load($file, $format)
    {
        $result = array();

        foreach ($this->factory->getLoader($format)->load($file, '-')->all() as $domain => $messages) {
            foreach ($messages as $text => $translation) {
                $result[$text] = $translation;
            }
        }

        return $result;
    }
}
