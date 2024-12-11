<?php

namespace r3pt1s\betternpc\entity\dialogue\action\impl;

use pocketmine\player\Player;
use r3pt1s\betternpc\entity\BetterEntity;
use r3pt1s\betternpc\entity\dialogue\action\EntityDialogueButtonActions;
use r3pt1s\betternpc\entity\dialogue\action\IEntityDialogueButtonAction;

final class EntityDialogueButtonRunCommandAction implements IEntityDialogueButtonAction {

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
        return EntityDialogueButtonActions::ACTION_RUN_COMMAND;
    }

    public static function fromData(array $data): ?static {
        return null;
    }
}