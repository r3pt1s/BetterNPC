<?php

namespace r3pt1s\betternpc\entity\impl;

use pocketmine\entity\Location;
use pocketmine\entity\Zombie;
use pocketmine\nbt\tag\CompoundTag;
use r3pt1s\betternpc\entity\BetterEntity;
use r3pt1s\betternpc\entity\data\BetterEntityData;
use r3pt1s\betternpc\entity\trait\CommandTrait;
use r3pt1s\betternpc\entity\trait\EmoteTrait;
use r3pt1s\betternpc\entity\trait\EntityDataTrait;

final class BetterZombie extends Zombie implements BetterEntity {
    use CommandTrait, EmoteTrait, EntityDataTrait;

    public function __construct(BetterEntityData $entityData, Location $location, ?CompoundTag $nbt = null) {
        $this->entityData = $entityData;
        parent::__construct($location, $nbt);
        $this->loadData($nbt);
    }

    public static function isCompatible(BetterEntityData $entityData): bool {
        return true;
    }
}