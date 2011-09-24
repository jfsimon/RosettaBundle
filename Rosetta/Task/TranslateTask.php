<?php

namespace BeSimple\RosettaBundle\Rosetta\Task;

use BeSimple\RosettaBundle\Rosetta\Factory;
use BeSimple\RosettaBundle\Entity\Message;
use BeSimple\RosettaBundle\Entity\Translation;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class TranslateTask extends AbstractTask implements TaskInterface
{
    /**
     * @var Factory
     */
    private $factory;

    /**
     * @var string
     */
    private $fromLocale;

    /**
     * @var string[]
     */
    private $toLocales;

    /**
     * Constructor.
     *
     * @param EventDispatcher $dispatcher
     * @param Factory         $factory
     * @param string          $fromLocale
     * @param array           $toLocales
     */
    public function __construct(EventDispatcher $dispatcher, Factory $factory, $fromLocale, array $toLocales)
    {
        parent::__construct($dispatcher);

        $this->factory    = $factory;
        $this->fromLocale = $fromLocale;
        $this->toLocales  = $toLocales;
    }

    /**
     * {@inheritdoc}
     */
    protected function processMessage(Message $message)
    {
        $locales = array();


        foreach ($this->toLocales as $locale) {
            $filter = function(Translation $translation) use ($locale) {
                return $translation->getLocale() === $locale;
            };

            if ($message->getTranslations()->filter($filter)->count() === 0) {
                $locales[] = $locale;
            }
        }

        if (count($locales)) {
            $feedback = array();
            foreach ($this->getTranslations($message, $this->fromLocale, $locales) as $locale => $text) {
                $message->addTranslation(new Translation($locale, $text));
                $feedback[$locale] = $text;
            }

            $this->dispatchMessageProcessed($message, $feedback);
        } else {
            $this->dispatchMessageIgnored($message, 'Already translated');
        }

        return $message;
    }

    /**
     * Returns translations for given message.
     *
     * @param Message $message    A Message instance
     * @param string  $fromLocale The source locale
     * @param array   $toLocales  An array of locales
     *
     * @return array
     */
    private function getTranslations(Message $message, $fromLocale, array $toLocales)
    {
        if ($message->getIsChoice() || $message->getIsKey() || count($message->getParameters())) {
            $this->dispatchMessageIgnored($message, 'Translation impossible');

            return array();
        }

        return $this
            ->factory
            ->getTranslator()
            ->translate($message->getText(), $fromLocale, $toLocales)
            ->all()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getAction()
    {
        return 'auto translate messages';
    }

    /**
     * @param string $fromLocale
     *
     * @return TranslateTask
     */
    public function setFromLocale($fromLocale)
    {
        $this->fromLocale = $fromLocale;

        return $this;
    }

    /**
     * @return string
     */
    public function getFromLocale()
    {
        return $this->fromLocale;
    }

    /**
     * @param array $toLocales
     *
     * @return TranslateTask
     */
    public function setToLocales(array $toLocales)
    {
        $this->toLocales = $toLocales;

        return $this;
    }

    /**
     * @return array
     */
    public function getToLocales()
    {
        return $this->toLocales;
    }
}
