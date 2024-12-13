<?php

namespace r3pt1s\betternpc\entity\impl;

use pocketmine\entity\Location;
use pocketmine\entity\Zombie;
use pocketmine\nbt\tag\CompoundTag;
use r3pt1s\betternpc\entity\BetterEntity;
use r3pt1s\betternpc\entity\data\BetterEntityData;
use r3pt1s\betternpc\entity\trait\AnimationTrait;
use r3pt1s\betternpc\entity\trait\CommandTrait;
use r3pt1s\betternpc\entity\trait\EmoteTrait;
use r3pt1s\betternpc\entity\trait\EntityDataTrait;

final class BetterZombie extends Zombie implements BetterEntity {
    use CommandTrait, EmoteTrait, AnimationTrait, EntityDataTrait;

    public function __construct(BetterEntityData $entityData, Location $location, ?CompoundTag $nbt = null) {
        $this->entityData = $entityData;
        parent::__construct($location, $nbt);
        $this->setNameTag($this->entityData->getNameTag());
        $this->setScoreTag($this->entityData->getScoreTag());
        $this->setScale($this->entityData->getScale());
        $this->setNameTagAlwaysVisible();
    }

    public function setNameTag(string $name): void {
        parent::setNameTag($name);
        $this->entityData->setNameTag($name);
    }

    public function setScoreTag(string $score): void {
        parent::setScoreTag($score);
        $this->entityData->setScoreTag($score);
    }

    public function setScale(float $value): void {
        parent::setScale($value);
        $this->entityData->setScale($value);
    }

    public function saveNBT(): CompoundTag {
        $nbt = parent::saveNBT();
        $nbt->setTag("entityData", $this->entityData->toNbt());
        return $nbt;
    }

    public static function isCompatible(BetterEntityData $entityData): bool {
        return true;
    }
}