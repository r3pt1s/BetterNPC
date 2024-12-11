<?php

namespace r3pt1s\betternpc\entity\action\impl;

use pocketmine\player\Player;
use r3pt1s\betternpc\entity\action\EntityActionIds;
use r3pt1s\betternpc\entity\action\IEntityAction;
use r3pt1s\betternpc\entity\BetterEntity;

final class EntityRunCommandAction implements IEntityAction {

    public function __construct(private string $command) {}

    public function doAction(Player $player, BetterEntity $entity): void {
        $player->chat($this->command);
    }

    public function setCommand(string $command): void {
        $this->command = $command;
    }

    public function getCommand(): string {
        return $this->command;
    }

    public function getData(): array {
        return ["command" => $this->command];
    }

    public function getId(): int {
        return EntityActionIds::ACTION_RUN_COMMAND;
    }

    public static function fromData(array $data): ?static {
        return null;
    }
}