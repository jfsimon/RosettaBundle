<?php

namespace BeSimple\RosettaBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use BeSimple\RosettaBundle\DependencyInjection\Compiler\ScannerPass;
use BeSimple\RosettaBundle\DependencyInjection\Compiler\TranslatorPass;
use BeSimple\RosettaBundle\DependencyInjection\Compiler\ParametersGuesserPass;
use BeSimple\RosettaBundle\DependencyInjection\Compiler\FactoryPass;
use BeSimple\RosettaBundle\DependencyInjection\Compiler\TasksPass;

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
}
