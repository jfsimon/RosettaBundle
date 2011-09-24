<?php

namespace BeSimple\RosettaBundle\Command\Helper;

use Symfony\Component\Console\Helper\DialogHelper as BaseDialogHelper;
use Symfony\Component\Console\Output\OutputInterface;
use BeSimple\RosettaBundle\Command\Helper\FormatterHelper;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class DialogHelper extends BaseDialogHelper
{
    private $formatter;

    public function __construct(FormatterHelper $formatter)
    {
        $this->formatter = $formatter;
    }

    public function ask(OutputInterface $output, $question, $default = null, array $choices = array())
    {
        $question = "\n".sprintf('<fg=blue>%s?</fg=blue>', $question);

        if (count($choices)) {
            foreach ($choices as $index => $choice) {
                if ($choice === $default) {
                    $choices[$index] = strtoupper($choice);
                }
            }

            $question.= sprintf(' <comment>[%s]</comment>', implode('/', $choices));
        }

        $question.= ' ';

        parent::ask($output, $question, $default);
    }

    public function askConfirmation(OutputInterface $output, $question, $default = true)
    {
        $answer  = 'z';
        $choices = array('y', 'n');

        while ($answer && !in_array(strtolower($answer[0]), $choices)) {
            $answer = $this->ask($output, $question, $default ? 'y' : 'n', $choices);
        }

        return $default === false
            ? $answer && 'y' == strtolower($answer[0])
            : !$answer || 'y' == strtolower($answer[0]);
    }
}
