<?php

namespace BeSimple\RosettaBundle\Rosetta\Workflow;

use BeSimple\RosettaBundle\Entity\Manager\GroupManager;
use BeSimple\RosettaBundle\Entity\Manager\MessageManager;
use BeSimple\RosettaBundle\Entity\Manager\TranslationManager;
use BeSimple\RosettaBundle\Entity\GroupCollection;
use BeSimple\RosettaBundle\Rosetta\Task\TaskInterface;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class Processor
{
    /**
     * @var GroupManager
     */
    private $groupManager;

    /**
     * @var MessageManager
     */
    private $messageManager;

    /**
     * @var TranslationManager
     */
    private $translationManager;

    /**
     * @var integer
     */
    private $batchLimit;

    /**
     * @var GroupCollection
     */
    private $groupsCache;

    /**
     * @var \Closure
     */
    private $taskFilter;

    /**
     * Constructor.
     *
     * @param GroupManager       $groupManager       A groups manager
     * @param MessageManager     $messageManager     A messages manager
     * @param TranslationManager $translationManager A translations manager
     * @param $batchLimit        int                 Batch limit
     */
    public function __construct(GroupManager $groupManager, MessageManager $messageManager, TranslationManager $translationManager, $batchLimit)
    {
        $this->groupManager       = $groupManager;
        $this->messageManager     = $messageManager;
        $this->translationManager = $translationManager;
        $this->batchLimit         = $batchLimit;
        $this->groupsCache        = new GroupCollection();
        $this->taskFilter         = null;
    }

    /**
     * Process an input stack.
     *
     * @param Inputs $inputs An input stack
     * @param Tasks  $tasks  A task stack
     *
     * @return int Processed inputs count
     */
    public function process(InputCollection $inputs, Tasks $tasks)
    {
        if (!$inputs->isValid()) {
            throw new \InvalidArgumentException('Inputs is invalid.');
        }

        $count = 0;
        while ($inputs->count() > 0) {
            $count += $this->processBatch($inputs->extract($this->batchLimit), $tasks);
        }

        return $count;
    }

    /**
     * Resets internal cache.
     *
     * @return Processor This instance
     */
    public function resetCache()
    {
        $this->groupsCache = new GroupCollection();

        return $this;
    }

    /**
     * Sets or removes task filter.
     *
     * @param \Closure|null $taskFilter A task filter
     *
     * @return Processor This instance
     */
    public function setTaskFilter(\Closure $taskFilter = null)
    {
        $this->taskFilter = $taskFilter;

        return $this;
    }

    /**
     * Returns task filter.
     *
     * @return \Closure|null A task filter
     */
    public function getTaskFilter()
    {
        return $this->taskFilter;
    }

    /**
     * @param Inputs $inputs
     * @param Tasks  $tasks
     *
     * @return integer
     */
    private function processBatch(InputCollection $inputs, Tasks $tasks)
    {
        $messages = array();

        foreach ($inputs->all() as $input) {
            $messages[] = $this->manage($input);
        }

        foreach ($tasks->actives() as $name => $task) {
            if ($this->processTask($task)) {
                $messages = $task->process($messages);

                if (count($messages) === 0) {
                    return 0;
                }
            }
        }

        foreach ($messages as $message) {
            $this->messageManager->cleanup($message);
        }

        $this->messageManager->flush();

        return count($messages);
    }

    /**
     * @param TaskInterface $task
     *
     * @return bool
     */
    private function processTask(TaskInterface $task)
    {
        if (is_null($this->taskFilter)) {
            return true;
        }

        $filter = $this->taskFilter;

        return (boolean) $filter($task);
    }

    /**
     * @param Input $input
     * @param bool  $withTranslations
     *
     * @return Message
     */
    private function manage(Input $input)
    {
        $dontSearchMessage      = false;
        $dontSearchTranslations = false;

        if (!$group = $this->groupsCache->find($input->getBundle(), $input->getDomain())) {
            if (!$group = $this->groupManager->findOneByBundleAndDomain($input->getBundle(), $input->getDomain())) {
                $group = $this->groupManager->create($input->getBundle(), $input->getDomain());

                $dontSearchMessage      = true;
                $dontSearchTranslations = true;
            }

            $this->groupsCache->add($group);
        }

        if ($dontSearchMessage || !$message = $this->messageManager->findOneByGroupAndText($group, $input->getText(), true)) {
            $message = $this->messageManager->create($input->getText(), $input->getParameters())->setIsChoice($input->getIsChoice());
            $dontSearchTranslations = true;
        }

        $group->addMessage($message);

        foreach ($input->getTranslations() as $locale => $texts) {
            foreach ($texts as $text) {
                if ($dontSearchTranslations || !$translation = $this->translationManager->findOneByMessageLocaleAndText($message, $locale, $text)) {
                    $translation = $this->translationManager->create($locale, $text);
                }

                $message->addTranslation($translation);
            }
        }

        return $message;
    }
}
