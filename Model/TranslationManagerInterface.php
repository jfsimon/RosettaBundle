<?php

namespace BeSimple\RosettaBundle\Model;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
interface TranslationManagerInterface
{
    /**
     * Creates a translation.
     *
     * @param Message $message
     * @param string  $locale
     * @param string  $text
     *
     * @return Translation A translation
     */
    function create(Message $message, $locale, $text);

    /**
     * Manages a translation.
     *
     * @param Translation $translation
     */
    function manage(Translation $translation);

    /**
     * Deletes a translation.
     *
     * @param Translation $translation
     */
    function delete(Translation $translation);

    /**
     * Applies changes in the database.
     */
    function flush();

    /**
     * Finds all translations from the database.
     *
     * @return array An array of translations
     */
    function findAll();

    /**
     * Finds translations by criteria.
     *
     * @param array $criteria
     *
     * @return array An array of translations
     */
    function findBy(array $criteria);

    /**
     * Finds one translation by criteria.
     *
     * @param array $criteria
     *
     * @return Translation|null A translation or null
     */
    function findOneBy(array $criteria);

    /**
     * Finds translations by message.
     *
     * @param Message $message
     *
     * @return array An array of translations
     */
    function findByMessage(Message $message);

    /**
     * Finds translations by message and locale.
     *
     * @param Message $message
     * @param string  $locale
     *
     * @return array An array of translations
     */
    function findByMessageAndLocale(Message $message, $locale);

    /**
     * Finds one translation by message, locale and text.
     *
     * @param Message $message
     * @param string  $locale
     * @param string  $text
     *
     * @return Translation|null A translation or null
     */
    function findOneByMessageLocaleAndText(Message $message, $locale, $text);

    /**
     * Finds selected translations by message.
     *
     * @param Message $message
     *
     * @return array An array of translations
     */
    function findSelectedByMessage(Message $message);

    /**
     * Finds one selected translation by message and locale.
     *
     * @param Message $message
     * @param string  $locale
     *
     * @return Translation|null A translation or null
     */
    function findOneSelectedByMessageAndLocale(Message $message, $locale);
}
