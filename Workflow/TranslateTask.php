<?php

namespace BeSimple\RosettaBundle\Workflow;

use BeSimple\RosettaBundle\Model\Message;
use BeSimple\RosettaBundle\Model\DomainCollection;

class TranslateTask implements TaskInterface
{
    private $translator;
    private $inputLocale;
    private $outputLocales;

    public function __construct(Translator $translator, $inputLocale, array $outputLocales)
    {
        // remove inputLocale from outputLocales
        $key = array_search($inputLocale, $outputLocales);
        if ($key !== false) {
            unset($outputLocales[$key]);
        }

        $this->translator = $translator;
        $this->inputLocale = $inputLocale;
        $this->outpuLocales = $outputLocales;
    }

    public function run(DomainCollection $domains)
    {
        $domains->walkMessages(array($this, 'translateMessage'));

        return $domains;
    }

    public function translateMessage(Message &$message)
    {
        $locales = array();

        foreach ($this->outputLocales as $locale) {
            if (!$message->hasLocaleTranslation($outputLocale)) {
                $locales[] = $locale;
            }
        }

        if (count($locales)) {
            $translations = $this->translator->translate($message->getText(), $this->inputLocale, $locales);

            foreach ($translations as $locale => $translation) {
                $message->addLocaleTranslation($locale, $translation);
            }
        }
    }
}