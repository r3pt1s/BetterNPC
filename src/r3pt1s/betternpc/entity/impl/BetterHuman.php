<?php

namespace r3pt1s\betternpc\entity\impl;

use pocketmine\entity\Human;
use pocketmine\entity\Location;
use pocketmine\nbt\tag\CompoundTag;
use r3pt1s\betternpc\entity\BetterEntity;
use r3pt1s\betternpc\entity\data\BetterEntityData;
use r3pt1s\betternpc\entity\trait\AnimationTrait;
use r3pt1s\betternpc\entity\trait\CommandTrait;
use r3pt1s\betternpc\entity\trait\EmoteTrait;
use r3pt1s\betternpc\entity\trait\EntityDataTrait;

final class BetterHuman extends Human implements BetterEntity {
    use CommandTrait, EmoteTrait, AnimationTrait, EntityDataTrait;

    public function __construct(BetterEntityData $entityData, Location $location, ?CompoundTag $nbt = null) {
        $this->entityData = $entityData;
        parent::__construct($location, $this->entityData->getSkinModel()->buildSkin(), $nbt);
        $this->setNameTag($this->entityData->getNameTag());
        $this->setScoreTag($this->entityData->getScoreTag());
        $this->setScale($this->entityData->getScale());
        $this->setNameTagAlwaysVisible($this->entityData->getSettings()->isNameTagAlwaysVisible());
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

    public function setNameTagAlwaysVisible(bool $value = true): void {
        parent::setNameTagAlwaysVisible($value);
        $this->entityData->getSettings()->setNameTagAlwaysVisible($value);
    }

    public function onUpdate(int $currentTick): bool {
        $this->emoteTick();
        return parent::onUpdate($currentTick);
    }

    public function saveNBT(): CompoundTag {
        $nbt = parent::saveNBT();
        $nbt->setTag("entityData", $this->entityData->toNbt());
        return $nbt;
    }

    public static function isCompatible(BetterEntityData $entityData): bool {
        return $entityData->getSkinModel()?->isValid() ?? false;
    }
}