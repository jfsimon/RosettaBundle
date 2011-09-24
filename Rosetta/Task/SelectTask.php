<?php

namespace BeSimple\RosettaBundle\Rosetta\Task;

use BeSimple\RosettaBundle\Entity\Message;
use BeSimple\RosettaBundle\Entity\Translation;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class SelectTask extends AbstractTask implements TaskInterface
{
    /**
     * @var int|null
     */
    private $minRating;

    /**
     * Contructor.
     *
     * @param EventDispatcher $dispatcher
     * @param int|null        $minRating
     */
    public function __construct(EventDispatcher $dispatcher, $minRating = null)
    {
        parent::__construct($dispatcher);

        $this->minRating = $minRating;
    }

    /**
     * {@inheritdoc}
     */
    protected function processMessage(Message $message)
    {
        $feedback = array();

        foreach ($this->getMissingSelectionLocales($message) as $locale) {
            $rating   = -INF;
            $selected = null;

            foreach ($message->getTranslations() as $translation) {
                if ($translation->getLocale() === $locale && $translation->getRating() > $rating && (is_null($this->minRating) || $rating >= $this->minRating)) {
                    $selected = &$translation;
                }
            }

            if (!is_null($selected)) {
                $selected->setIsSelect(true);
                $feedback[(string) $selected->getLocale()] = $selected->getText();
            }
        }

        if (count($feedback)) {
            $this->dispatchMessageProcessed($message, $feedback);
        } else {
            $this->dispatchMessageIgnored($message, 'No translation selected');
        }

        return $message;
    }

    /**
     * Finds locales with no selected translation for given message.
     *
     * @param Message $message A message instance
     *
     * @return array An array of locales
     */
    private function getMissingSelectionLocales(Message $message)
    {
        $locales = array();
        $ignore  = array();

        foreach ($message->getTranslations() as $translation) {
            if (!in_array($translation->getLocale(), $locales)) {
                $locales[] = $translation->getLocale();
            }

            if ($translation->getIsSelected()) {
                $ignore[] = $translation->getLocale();
            }
        }

        return array_diff($locales, $ignore);
    }

    /**
     * {@inheritdoc}
     */
    public function getAction()
    {
        return 'auto select best translations';
    }

    /**
     * @param int $minRating
     *
     * @return SelectTask
     */
    public function setMinRating($minRating)
    {
        $this->minRating = $minRating;

        return $this;
    }

    /**
     * @return int
     */
    public function getMinRating()
    {
        return $this->minRating;
    }
}
