<?php

namespace r3pt1s\betternpc\entity\trait;

use pocketmine\console\ConsoleCommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

trait CommandTrait {

    protected array $commands = [];

    public function execute(?Player $player): void {
        if ($player === null) { //handled by server
            foreach ($this->commands as $command) Server::getInstance()->dispatchCommand(new ConsoleCommandSender(Server::getInstance(), Server::getInstance()->getLanguage()), $command);
            return;
        }

        foreach ($this->commands as $command) {
            $player->chat($command);
        }
    }

    public function addCommand(string $command): void {
        if (!$this->checkCommand($command)) $this->commands[] = $command;
    }

    public function removeCommand(string $command): void {
        if ($this->checkCommand($command)) unset($this->commands[array_search($command, $this->commands)]);
    }

    public function checkCommand(string $command): bool {
        return in_array($command, $this->commands);
    }

    public function getCommands(): array {
        return $this->commands;
    }
}