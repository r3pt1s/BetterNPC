<?php

namespace r3pt1s\betternpc\entity\action\impl;

use pocketmine\player\Player;
use r3pt1s\betternpc\entity\action\EntityActionIds;
use r3pt1s\betternpc\entity\action\IEntityAction;
use r3pt1s\betternpc\entity\BetterEntity;
use pocketmine\nbt\tag\CompoundTag;

final class EntityRunCommandAction implements IEntityAction {

    public function __construct() {}

    public function doAction(Player $player, BetterEntity $entity): void {
        $entity->runCommands($player);
    }

    public function getId(): int {
        return EntityActionIds::ACTION_RUN_COMMAND;
    }

    public function toNbt(): CompoundTag {
        return CompoundTag::create();
    }

    public static function fromNbt(CompoundTag $nbt): EntityRunCommandAction {
        return new self();
    }

    public static function create(): self {
        return new self();
    }
}