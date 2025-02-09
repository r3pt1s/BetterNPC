<?php

namespace r3pt1s\betternpc\entity\trait;

use pocketmine\console\ConsoleCommandSender;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\player\Player;
use pocketmine\Server;
use r3pt1s\betternpc\entity\util\EntityGlobalSettings;

trait CommandTrait {

    protected array $commands = [];

    public function loadCommands(?ListTag $nbt): void {
        $this->commands = $this->convertNbtToCommands($nbt);
    }

    public function runCommands(Player $player): void {
        $serverHandling = EntityGlobalSettings::getBoolSetting(EntityGlobalSettings::KEY_SERVER_COMMAND_HANDLING);
        foreach ($this->commands as $command) {
            $command = str_replace(["{player}"], [$player->getName()], $command);
            if ($serverHandling) {
                Server::getInstance()->dispatchCommand(new ConsoleCommandSender(Server::getInstance(), Server::getInstance()->getLanguage()), $command);
            } else {
                $player->chat($command);
            }
        }
    }

    public function addCommand(string $command): void {
        if (!$this->checkCommand($command)) $this->commands[] = $command;
    }

    public function removeCommand(string $command): void {
        if ($this->checkCommand($command)) {
            unset($this->commands[array_search($command, $this->commands)]);
            $this->commands = array_values($this->commands);
        }
    }

    public function checkCommand(string $command): bool {
        return in_array($command, $this->commands);
    }

    public function convertCommandsToNbt(): ListTag {
        return new ListTag(array_map(fn(string $command) => new StringTag($command), $this->commands));
    }

    public function getCommands(): array {
        return $this->commands;
    }

    private function convertNbtToCommands(?ListTag $nbt): array {
        if (!$nbt instanceof ListTag) return [];
        $commands = [];
        foreach ($nbt->getValue() as $tag) {
            if ($tag instanceof StringTag) $commands[] = $tag->getValue();
        }

        return $commands;
    }
}