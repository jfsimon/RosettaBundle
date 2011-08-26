<?php

namespace BeSimple\RosettaBundle\Translation\Dumper;

use Symfony\Component\HttpKernel\Util\Filesystem;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class AbstractFileDumper extends AbstractDumper
{
    /**
     * Writes content into resource file.
     *
     * @param string $resource A file path
     * @param string $content  A content to write
     */
    protected function write($resource, $content)
    {
        $directory = dirname($resource);
        if (!file_exists($directory)) {
            $fs = new Filesystem();
            $fs->mkdir($directory, 0755);
        }

        if (false === file_put_contents($resource, $content)) {
            throw new \RuntimeException('File "'.$resource.'" could not be written');
        }
    }
}
