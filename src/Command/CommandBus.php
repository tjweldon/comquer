<?php declare(strict_types=1);

namespace CQRS\Command;

use CQRS\Bus\BusException;
use CQRS\HandlerProvider;

class CommandBus
{
    private $registeredCommands;

    private $handlerProvider;

    public function __construct(RegisteredCommands $registeredCommands, HandlerProvider $handlerProvider)
    {
        $this->registeredCommands = $registeredCommands;
        $this->handlerProvider = $handlerProvider;
    }

    public function handle($command)
    {
        if ($this->registeredCommands->contains($command) === false) {
            throw BusException::classNotRegistered(get_class($command));
        }

        $handler = $this->handlerProvider->get(
            $this->registeredCommands->getHandlerClassName($command)
        );

        return $handler->handle($command);
    }
}