<?php

namespace BeSimple\RosettaBundle\Model;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
interface MessageManagerInterface
{
    /**
     * Creates a message.
     *
     * @param Group $group
     * @param string $text
     * @param array $parameters
     *
     * @return Message
     */
    function create(Group $group, $text, array $parameters = array());

    /**
     * Manages a message.
     *
     * @param Message $message
     */
    function manage(Message $message);

    /**
     * Deletes a message.
     *
     * @param Message $message
     */
    function delete(Message $message);

    /**
     * Applies changes in database.
     */
    function flush();

    /**
     * Finds all messages from database.
     *
     * @param bool $withTranslations Joins translations if true
     *
     * @return array An array of messages
     */
    function findAll($withTranslations = false);

    /**
     * Finds messages by criteria.
     *
     * @param array $criteria
     * @param bool  $withTranslations Joins translations if true
     *
     * @return array An array of messages
     */
    function findBy(array $criteria, $withTranslations = false);

    /**
     * Finds one message by criteria.
     *
     * @param array $criteria
     * @param bool  $withTranslations  Joins translations if true
     *
     * @return Message|null A message or null
     */
    function findOneBy(array $criteria, $withTranslations = false);

    /**
     * Finds all messages by group.
     *
     * @param Group $group
     * @param bool  $withTranslations  Joins translations if true
     *
     * @return array An array of messages
     */
    function findByGroup(Group $group, $withTranslations = false);

    /**
     * Finds one message by group and text.
     *
     * @param Group  $group
     * @param string $text
     * @param bool   $withTranslations  Joins translations if true
     *
     * @return Message|null A message or null
     */
    function findOneByGroupAndText(Group $group, $text, $withTranslations = false);
}
