<?php

namespace BeSimple\RosettaBundle\Model;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
abstract class MessageManager
{
    /**
     * @var string
     */
    protected $class;

    /**
     * {@inheritdoc}
     */
    public function create(Group $group, $text, array $parameters = array())
    {
        $message = new $this->class($group, $text, $parameters);
        $this->manage($message);

        return $message;
    }

    /**
     * {@inheritdoc}
     */
    public function findByGroup(Group $group, $withTranslations = false)
    {
        return $this->findBy(array('group' => $group->getId()), $withTranslations);
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByGroupAndText(Group $group, $text, $withTranslations = false)
    {
        return $this->findOneBy(array('group' => $group->getId(), 'hash' => MessageText::hash($text)), $withTranslations);
    }
}
