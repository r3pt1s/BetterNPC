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
    }
    
    public function isCompatible(BetterEntityData $entityData): bool {
        return $entityData->getSkinModel()?->isValid() ?? false;
    }
}