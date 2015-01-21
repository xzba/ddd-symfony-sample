<?php

namespace Matthias\Common\App\Infrastructure\AsynchronousBusBundle;

use Matthias\Common\App\Infrastructure\AsynchronousBusBundle\DependencyInjection\CommandBusExtension;
use Matthias\Common\App\Infrastructure\AsynchronousBusBundle\DependencyInjection\Compiler\ConfigureMiddlewares;
use Matthias\Common\App\Infrastructure\AsynchronousBusBundle\DependencyInjection\Compiler\RegisterHandlers;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AsynchronousBusCommandBusBundle extends Bundle
{
    private $configurationAlias;

    public function __construct($alias = 'asynchronous_command_bus')
    {
        $this->configurationAlias = $alias;
    }

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(
            new ConfigureMiddlewares(
                'asynchronous_command_bus',
                'asynchronous_command_bus_middleware'
            )
        );

        $container->addCompilerPass(
            new RegisterHandlers(
                'asynchronous_bus.asynchronous_command_bus.command_handler_map',
                'command_handler',
                'handles'
            )
        );
    }

    public function getContainerExtension()
    {
        return new CommandBusExtension($this->configurationAlias);
    }
}
