<?php

namespace Matthias\Common\App\Infrastructure\AsynchronousBusBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RegisterMessageRecorders implements CompilerPassInterface
{
    private $aggregatorId;
    private $recorderTag;

    /**
     * @param string  $aggregatorId         The id of the service with class AggregatesRecordedMessages
     * @param string  $recorderTag          The tag name of message recorder services
     */
    public function __construct($aggregatorId, $recorderTag)
    {
        $this->aggregatorId = $aggregatorId;
        $this->recorderTag = $recorderTag;
    }

    public function process(ContainerBuilder $container)
    {
        $aggregator = $container->findDefinition($this->aggregatorId);

        $recorders = array();
        foreach (array_keys($container->findTaggedServiceIds($this->recorderTag)) as $recorderId) {
            $recorders[] = new Reference($recorderId);
        }

        $aggregator->replaceArgument(0, $recorders);
    }
}
