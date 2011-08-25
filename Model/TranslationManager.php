<?php

namespace BeSimple\RosettaBundle\Model;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
abstract class TranslationManager
{
    /**
     * @var string
     */
    protected $class;

    /**
     * {@inheritdoc}
     */
    public function create(Message $message, $locale, $text)
    {
        $message = new $this->class($message, $locale, $text);
        $this->manage($message);

        return $message;
    }

    /**
     * {@inheritdoc}
     */
    public function findByMessage(Message $message)
    {
        return $this->findBy(array('message' => $message->getId()));
    }

    /**
     * {@inheritdoc}
     */
    public function findByMessageAndLocale(Message $message, $locale)
    {
        return $this->findBy(array('message' => $message->getId(), 'locale' => $locale));
    }

    /**
     * {@inheritdoc}
     */
    public function findSelectedByMessage(Message $message)
    {
        return $this->findBy(array('message' => $message->getId(), 'isSelected' => true));
    }

    /**
     * {@inheritdoc}
     */
    public function findOneSelectedByMessageAndLocale(Message $message, $locale)
    {
        return $this->findOneBy(array('message' => $message->getId(), 'isSelected' => true, 'locale' => $locale));
    }
}
