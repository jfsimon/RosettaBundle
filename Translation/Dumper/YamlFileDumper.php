<?php

namespace BeSimple\RosettaBundle\Translation\Dumper;

use Symfony\Component\Yaml\Yaml;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class YamlFileDumper extends AbstractFileDumper implements DumperInterface
{
    /**
     * {@inheritdoc}
     */
    public function dump($resource, array $messages, $sourceLocale = null, $targetLocale = null)
    {
        $this->expand($messages);
        $this->write($resource, Yaml::dump($messages));
    }
}
