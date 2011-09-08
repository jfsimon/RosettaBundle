<?php

namespace BeSimple\RosettaBundle\Entity;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class TranslationManager extends AbstractManager
{
    const ENTITY_CLASS = '\\BeSimple\\RosettaBundle\\Entity\\Translation';

    /**
     * Creates a translation.
     *
     * @param string  $locale  A locale
     * @param string  $text    A text
     *
     * @return Translation A Translation instance
     */
    public function create($locale, $text)
    {
        $translation = new Translation($locale, $text);
        $this->manage($translation);

        return $translation;
    }

    /**
     * Manages a translation.
     *
     * @param Translation $translation A Translation instance
     *
     * @return TranslationManager This instance
     */
    public function manage(Translation $translation)
    {
        $this->manager->persist($translation);

        return $this;
    }

    /**
     * Cleanups a translation.
     *
     * @param Translation $translation A Translation instance
     *
     * @return TranslationManager This instance
     */
    public function cleanup(Translation $translation)
    {
        $translation->cleanup($this->helper);

        return $this;
    }

    /**
     * Removes a translation.
     *
     * @param Translation $translation A Translation instance
     *
     * @return TranslationManager This instance
     */
    public function remove(Translation $translation)
    {
        $this->manager->remove($translation);

        return $this;
    }

    /**
     * Finds translations by message.
     *
     * @param Message $message A Message instance
     *
     * @return array An array of translations
     */
    public function findByMessage(Message $message)
    {
        return $this->findBy(array('message' => $message->getId()));
    }

    /**
     * Finds translations by message and locale.
     *
     * @param Message $message A Message instance
     * @param string  $locale  A locale
     *
     * @return array An array of translations
     */
    public function findByMessageAndLocale(Message $message, $locale)
    {
        return $this->findBy(array('message' => $message->getId(), 'locale' => $locale));
    }

    /**
     * Finds one translation by message, locale and text.
     *
     * @param Message $message A Message instance
     * @param string  $locale  A locale
     * @param string  $text    A text
     *
     * @return Translation|null A translation or null
     */
    public function findOneByMessageLocaleAndText(Message $message, $locale, $text)
    {
        return $this->findBy(array('message' => $message->getId(), 'locale' => $locale, 'hash' => $this->helper->hash($text)));
    }

    /**
     * Finds selected translations by message.
     *
     * @param Message $message A Message instance
     *
     * @return array An array of translations
     */
    public function findSelectedByMessage(Message $message)
    {
        return $this->findBy(array('message' => $message->getId(), 'isSelected' => true));
    }

    /**
     * Finds one selected translation by message and locale.
     *
     * @param Message $message A Message instance
     * @param string  $locale  A locale
     *
     * @return Translation|null A translation or null
     */
    public function findOneSelectedByMessageAndLocale(Message $message, $locale)
    {
        return $this->findOneBy(array('message' => $message->getId(), 'isSelected' => true, 'locale' => $locale));
    }
}
