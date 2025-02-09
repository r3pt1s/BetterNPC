<?php

namespace r3pt1s\betternpc\listener;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerEntityInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\player\Player;
use r3pt1s\betternpc\entity\BetterEntity;
use r3pt1s\betternpc\entity\BetterEntityTypes;
use r3pt1s\betternpc\form\FormManager;
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

    public function onDamage(EntityDamageEvent $event): void {
        if (BetterEntityTypes::checkClass($event->getEntity()::class)) $event->cancel();
    }

    public function onHit(EntityDamageByEntityEvent $event): void {
        /** @var BetterEntity $entity */
        $entity = $event->getEntity();
        $player = $event->getDamager();

        if ($player instanceof Player && BetterEntityTypes::checkClass($entity::class)) {
            if (PlayerSession::get($player)->isTryingToRemove()) {
                $entity->flagForDespawn();
                PlayerSession::get($player)->setTryingToRemove(false);
                $player->sendMessage(Main::PREFIX . "§aSuccessfully §cremoved §7the entity!");
                return;
            }

            if (PlayerSession::get($player)->isTryingToEdit()) {
                PlayerSession::get($player)->setTryingToEdit(false);
                $player->sendForm(FormManager::editEntityForm($entity));
                return;
            }

            if (!PlayerSession::get($player)->checkCoolDown()) {
                PlayerSession::get($player)->setCoolDown(10);
                $entity->onHit($player);
            }
        }
    }

    public function onEntityInteract(PlayerEntityInteractEvent $event): void {
        $player = $event->getPlayer();
        $session = PlayerSession::get($player);
        $entity = $event->getEntity();

        if ($entity instanceof BetterEntity) {
            if ($session->isTryingToRemove()) {
                $entity->flagForDespawn();
                PlayerSession::get($player)->setTryingToRemove(false);
                $player->sendMessage(Main::PREFIX . "§aSuccessfully §cremoved §7the entity!");
                return;
            }

            if ($session->isTryingToEdit()) PlayerSession::get($player)->setTryingToEdit(false);
            if ($player->hasPermission("betternpc.edit")) {
                if (!$session->checkCoolDown()) {
                    $session->setCoolDown(10);
                    $player->sendForm(FormManager::editEntityForm($entity));
                }
            }
        }
    }
}