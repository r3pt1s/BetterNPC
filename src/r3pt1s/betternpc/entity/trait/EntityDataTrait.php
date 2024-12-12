<?php

namespace r3pt1s\betternpc\entity\trait;

use pocketmine\entity\Entity;
use pocketmine\player\Player;
use r3pt1s\betternpc\entity\data\BetterEntityData;

trait EntityDataTrait {

    protected BetterEntityData $entityData;

    public function onHit(Player $player): void {
        $this->entityData->getHitAction()?->doAction($player, $this);
    }

    public function getEntityData(): BetterEntityData {
        return $this->entityData;
    }

    public function getEntity(): Entity {
        return $this;
    }
}