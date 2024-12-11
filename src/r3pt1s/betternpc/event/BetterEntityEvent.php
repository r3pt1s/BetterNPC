<?php

namespace r3pt1s\betternpc\event;

use pocketmine\event\Event;
use r3pt1s\betternpc\entity\BetterEntity;

abstract class BetterEntityEvent extends Event {

    public function __construct(
        private readonly BetterEntity $entity
    ) {}

    public function getEntity(): BetterEntity {
        return $this->entity;
    }
}