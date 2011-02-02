<?php

namespace Bundle\RosettaBundle\Service\Deployer;

class YamlDumper extends Dumper implements DumperInterface
{
    public function render(array $translations)
    {
        $output = 'return array('."\n";

        foreach ($translations as $source => $translation) {
            $output.= '    '.$this->getString($source);
            $output.= ' => '.$this->getString($translation);
            $output.= ','."\n";
        }

        $output.= ')';

        return $output;
    }

    protected function getExtension()
    {
        return 'php';
    }

    protected function getString($string)
    {
        $search = array('\'', '\\');
        $replace = array('\\\'', '\\\\');

        return '\''.str_replace($search, $replace, $string).'\'';
    }
}