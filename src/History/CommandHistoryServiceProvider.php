<?php

namespace Jakmall\Recruitment\Calculator\History;

use Illuminate\Contracts\Container\Container;
use Jakmall\Recruitment\Calculator\Container\ContainerServiceProviderInterface;
use Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;
use Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerImp;

class CommandHistoryServiceProvider implements ContainerServiceProviderInterface
{
    /**
     * @inheritDoc
     */
    public function register(Container $container): void
    {
        $container->bind(
            CommandHistoryManagerInterface::class,
            function () {
                //todo: register implementation
                return new CommandHistoryManagerImp();
            }
        );
    }
}
