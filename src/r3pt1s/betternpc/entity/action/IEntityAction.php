<?php

namespace r3pt1s\betternpc\entity\action;

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;
use r3pt1s\betternpc\entity\BetterEntity;

interface IEntityAction {

    public function doAction(Player $player, BetterEntity $entity): void;

    public function getId(): int;

    public function toNbt(): CompoundTag;

    public static function fromNbt(CompoundTag $nbt): ?self;
}