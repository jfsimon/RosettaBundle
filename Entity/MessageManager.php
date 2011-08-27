<?php

namespace BeSimple\RosettaBundle\Entity;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class MessageManager extends AbstractManager
{
    const ENTITY_CLASS = '\\BeSimple\\RosettaBundle\\Entity\\Message';

    /**
     * Creates a message.
     *
     * @param Group  $group     A Group instance
     * @param string $text      A text
     * @param array $parameters An array of parameters
     *
     * @return Message A Message instance
     */
    public function create(Group $group, $text, array $parameters = array())
    {
        $message = new Message($group, $text, $parameters);
        $this->manage($message);

        return $message;
    }

    /**
     * Manages a message.
     *
     * @param Message $message A Message instance
     *
     * @return MessageManager This instance
     */
    public function manage(Message $group)
    {
        $this->manager->persist($group);

        return $this;
    }

    /**
     * Cleanups a message.
     *
     * @param Message $message A Message instance
     *
     * @return MessageManager This instance
     */
    public function cleanup(Message $message)
    {
        $message->cleanup($this->helper);

        return $this;
    }

    /**
     * Removes a message.
     *
     * @param Message $message A Message instance
     *
     * @return MessageManager This instance
     */
    public function remove(Message $group)
    {
        $this->manager->remove($group);

        return $this;
    }

    /**
     * Finds all messages by group.
     *
     * @param Group $group             A Group instance
     * @param bool  $withTranslations  Joins translations if true
     *
     * @return array An array of messages
     */
    public function findByGroup(Group $group, $withTranslations = false)
    {
        // todo: a query!
        //return $this->findBy(array('group' => $group->getId()), $withTranslations);
    }

    /**
     * Finds one message by group and text.
     *
     * @param Group  $group             A Group instance
     * @param string $text              A text
     * @param bool   $withTranslations  Joins translations if true
     *
     * @return Message|null A message or null
     */
    public function findOneByGroupAndText(Group $group, $text, $withTranslations = false)
    {
        // todo: a query!
        //return $this->findOneBy(array('group' => $group->getId(), 'hash' => $this->helper->hash($text)), $withTranslations);
    }
}
