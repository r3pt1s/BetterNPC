<?php

namespace r3pt1s\betternpc\entity;

use pocketmine\entity\Entity;
use pocketmine\entity\Location;
use pocketmine\nbt\tag\CompoundTag;
use r3pt1s\betternpc\entity\trait\AnimationTrait;
use r3pt1s\betternpc\entity\trait\CommandTrait;
use r3pt1s\betternpc\entity\trait\EmoteTrait;

abstract class BetterEntity extends Entity {
    use CommandTrait, EmoteTrait, AnimationTrait;

    //TODO: on hit action

    public function __construct(Location $location, ?CompoundTag $nbt = null) {
        parent::__construct($location, $nbt);
    }
}