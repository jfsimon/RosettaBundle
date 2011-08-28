<?php

namespace BeSimple\RosettaBundle\Tests\Rosetta\Workflow;

use BeSimple\RosettaBundle\Tests\AppTestCase;
use BeSimple\RosettaBundle\Entity\Helper;
use BeSimple\RosettaBundle\Rosetta\Workflow\Input;
use BeSimple\RosettaBundle\Rosetta\Workflow\InputCollection;
use BeSimple\RosettaBundle\Rosetta\Workflow\Tasks;
use BeSimple\RosettaBundle\Entity\Group;
use BeSimple\RosettaBundle\Entity\Message;
use BeSimple\RosettaBundle\Entity\Translation;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class ProcessorTest extends AppTestCase
{
    static public function setUpBeforeClass()
    {
        static::createKernel();
        static::createDatabase();
    }

    static public function tearDownAfterClass()
    {
        static::dropDatabase();
        static::destroyKernel();
    }

    /**
     * @dataProvider provideProcessData
     */
    public function testProcess(InputCollection $inputs, array $counts)
    {
        static::cleanDatabase();

        $container    = static::$kernel->getContainer();
        $tasks        = $container->get('be_simple_rosetta.tasks');
        $processor    = $container->get('be_simple_rosetta.processor')->resetCache()->process($inputs, $tasks);
        $groups       = $container->get('be_simple_rosetta.model.group_manager')->findAll();
        $messages     = $container->get('be_simple_rosetta.model.message_manager')->findAll();
        $translations = $container->get('be_simple_rosetta.model.translation_manager')->findAll();

        $this->assertEquals($counts['group'], count($groups));
        $this->assertEquals($counts['message'], count($messages));
        $this->assertEquals($counts['translation'], count($translations));

        foreach ($inputs->all() as $input) {
            $this->assertInputFound($input, $groups, $messages, $translations);
        }
    }


    public function provideProcessData()
    {
        // all differents
        $inputs1 = new InputCollection(array(
            new Input('bundle1', 'domain1', 'text1'),
            new Input('bundle1', 'domain2', 'text2'),
            new Input('bundle2', 'domain1', 'text3'),
            new Input('bundle2', 'domain2', 'text4'),
        ));

        // one group
        $inputs2 = new InputCollection(array(
            new Input('bundle', 'domain', 'text1'),
            new Input('bundle', 'domain', 'text2'),
            new Input('bundle', 'domain', 'text3'),
            new Input('bundle', 'domain', 'text4'),
        ));

        return array(
            array($inputs1, array('group' => 4, 'message' => 4, 'translation' => 0)),
            array($inputs2, array('group' => 1, 'message' => 4, 'translation' => 0)),
        );
    }

    protected function assertInputFound(Input $input, array $groups, array $messages, array $translations)
    {
        $helper = static::$kernel->getContainer()->get('be_simple_rosetta.model.helper');

        $foundGroup = null;
        foreach ($groups as $group) {
            if ($group->getBundle() === $input->getBundle() && $group->getDomain() === $input->getDomain()) {
                $foundGroup = $group;
            }
        }
        $this->assertTrue($foundGroup instanceof Group);

        $foundMessage = null;
        foreach ($messages as $message) {
            if ($message->getText() === $input->getText() && $message->getGroup()->getBundle() === $input->getBundle() && $message->getGroup()->getDomain() === $input->getDomain()) {
                $foundMessage = $message;
                $this->assertEquals($helper->hash($input->getText()), $message->getHash());
                $this->assertEquals($input->getIsChoice(), $message->getIsChoice());
                $this->assertEquals($input->getParameters(), $message->getParameters());
            }
        }
        $this->assertTrue($foundMessage instanceof Message);
        $this->assertEquals($foundMessage->getGroup(), $foundGroup);

        foreach ($input->getTranslations() as $locale => $texts) {
            foreach ($texts as $text) {
                $foundTranslation = null;
                foreach ($translations as $translation) {
                    if ($translation->getText() === $text && $translation->getLocale() === $locale) {
                        $foundTranslation = $translation;
                    }
                }
            }
            $this->assertTrue($foundTranslation instanceof Translation);
            $this->assertEquals($foundTranslation->getMessage(), $foundMessage);
        }
    }
}
