<?php

namespace r3pt1s\betternpc\listener;

use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\player\Player;
use r3pt1s\betternpc\entity\BetterEntity;
use r3pt1s\betternpc\entity\BetterEntityTypes;
use r3pt1s\betternpc\Main;
use r3pt1s\betternpc\player\PlayerSession;
use r3pt1s\betternpc\player\PlayerSessionCache;

final class EventListener implements Listener {

    public function onJoin(PlayerJoinEvent $event): void {
       PlayerSessionCache::create($event->getPlayer());
    }

    public function onQuit(PlayerQuitEvent $event): void {
        PlayerSessionCache::remove($event->getPlayer());
    }

    public function onHit(EntityDamageByEntityEvent $event): void {
        /** @var BetterEntity&Entity $entity */
        $entity = $event->getEntity();;
        $player = $event->getDamager();

        if ($player instanceof Player && BetterEntityTypes::checkClass($entity::class)) {
            $event->cancel();
            if (PlayerSession::get($player)->isTryingToRemove()) {
                $entity->flagForDespawn();
                PlayerSession::get($player)->setTryingToRemove(false);
                $player->sendMessage(Main::PREFIX . "§aSuccessfully §cremoved §7the entity!");
                return;
            }

            if (!PlayerSession::get($player)->checkCoolDown()) {
                PlayerSession::get($player)->setCoolDown(10);
                $entity->onHit($player);
            }
        }
    }
}