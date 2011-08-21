<?php

namespace BeSimple\RosettaBundle\Translation\Dumper;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class PhpFileDumper extends AbstractFileDumper implements DumperInterface
{
    /**
     * {@inheritdoc}
     */
    public function dump($resource, array $messages)
    {
        $this->expand($messages);
        $this->write($resource, 'return '.var_export($messages).';');
    }
}
