<?php

namespace r3pt1s\betternpc\listener;

use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\Server;
use r3pt1s\betternpc\entity\BetterEntity;
use r3pt1s\betternpc\entity\BetterEntityTypes;

final class EventListener implements Listener {

    private array $internalHitCoolDowns = [];
    //TODO edit form

    public function onNHit(EntityDamageByEntityEvent $event): void {
        /** @var BetterEntity&Entity $entity */
        $entity = $event->getEntity();;
        $player = $event->getDamager();

        if ($player instanceof Player && BetterEntityTypes::checkClass($entity::class)) {
            $event->cancel();
            if (!$this->checkCoolDown($player)) {
                $this->internalHitCoolDowns[$player->getName()] = Server::getInstance()->getTick() + 10;
                $entity->onHit($player);
            }
        }
    }

    private function checkCoolDown(Player $player): bool {
        if (isset($this->internalHitCoolDowns[$player->getName()])) {
            if (Server::getInstance()->getTick() < $this->internalHitCoolDowns[$player->getName()]) {
                return true;
            }

            unset($this->internalHitCoolDowns[$player->getName()]);
        }
        return false;
    }
}