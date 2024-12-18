<?php

namespace r3pt1s\betternpc\entity\action\impl;

use pocketmine\nbt\tag\StringTag;
use pocketmine\player\Player;
use r3pt1s\betternpc\entity\action\EntityActionIds;
use r3pt1s\betternpc\entity\action\IEntityAction;
use r3pt1s\betternpc\entity\BetterEntity;
use pocketmine\nbt\tag\CompoundTag;

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

    public function getId(): int {
        return EntityActionIds::ACTION_RUN_COMMAND;
    }

    public function toNbt(): CompoundTag {
        return CompoundTag::create()
            ->setString("command", $this->command);
    }

    public static function fromNbt(CompoundTag $nbt): ?EntityRunCommandAction {
        if ($nbt->getTag("command") instanceof StringTag) {
            return new self($nbt->getString("command"));
        }
        return null;
    }

    public static function create(string $command): self {
        return new self($command);
    }
}