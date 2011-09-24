<?php

namespace BeSimple\RosettaBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use BeSimple\RosettaBundle\DependencyInjection\Compiler\ScannerPass;
use BeSimple\RosettaBundle\DependencyInjection\Compiler\TranslatorPass;
use BeSimple\RosettaBundle\DependencyInjection\Compiler\ParametersGuesserPass;
use BeSimple\RosettaBundle\DependencyInjection\Compiler\FactoryPass;
use BeSimple\RosettaBundle\DependencyInjection\Compiler\TasksPass;
use Doctrine\DBAL\Types\Type as DoctrineType;



/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class BeSimpleRosettaBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ScannerPass());
        $container->addCompilerPass(new TranslatorPass());
        $container->addCompilerPass(new ParametersGuesserPass());
        $container->addCompilerPass(new FactoryPass());
        $container->addCompilerPass(new TasksPass());
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        DoctrineType::addType(
            'locale',
            $this->container->getParameter('be_simple_rosetta.model.locale_type')
        );

        $this
            ->container
            ->get('doctrine.orm.default_entity_manager')
            ->getConnection()
            ->getDatabasePlatform()
            ->registerDoctrineTypeMapping('locale', 'locale')
        ;
    }
}
