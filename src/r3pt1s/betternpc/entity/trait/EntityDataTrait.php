<?php

namespace r3pt1s\betternpc\entity\trait;

use r3pt1s\betternpc\entity\data\BetterEntityData;

trait EntityDataTrait {

    protected BetterEntityData $entityData;

    public function getEntityData(): BetterEntityData {
        return $this->entityData;
    }
}