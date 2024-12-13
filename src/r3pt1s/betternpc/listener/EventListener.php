<?php

namespace r3pt1s\betternpc\listener;

use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerEntityInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\network\mcpe\protocol\MoveActorAbsolutePacket;
use pocketmine\player\Player;
use pocketmine\world\Position;
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

    public function onMove(PlayerMoveEvent $event): void {
        $player = $event->getPlayer();

        /** @var BetterEntity $entity */
        foreach (array_filter($player->getworld()->getEntities(), fn(Entity $entity) => $entity instanceof BetterEntity) as $entity) {
            if ($entity->getEntityData()->getSettings()->isLookToPlayers()) {
                if ($entity->getPosition()->distance($player->getPosition()) <= 9) {
                    $horizontal = sqrt(($player->getPosition()->x - $entity->getPosition()->x) ** 2 + ($player->getPosition()->z - $entity->getLocation()->z) ** 2);
                    $vertical = $player->getPosition()->y - $entity->getLocation()->getY();
                    $pitch = -atan2($vertical, $horizontal) / M_PI * 180;

                    $xDist = $player->getPosition()->x - $entity->getLocation()->x;
                    $zDist = $player->getPosition()->z - $entity->getLocation()->z;

                    $yaw = atan2($zDist, $xDist) / M_PI * 180 - 90;
                    if ($yaw < 0) $yaw += 360.0;

                    $player->getNetworkSession()->sendDataPacket(MoveActorAbsolutePacket::create($entity->getId(), Position::fromObject($entity->getOffsetPosition($entity->getPosition()), $entity->getWorld()), $pitch, $yaw, $yaw, 0));
                }
            }
        }
    }

    public function onDamage(EntityDamageEvent $event): void {
        if (BetterEntityTypes::checkClass($event->getEntity()::class)) $event->cancel();
    }

    public function onHit(EntityDamageByEntityEvent $event): void {
        /** @var BetterEntity $entity */
        $entity = $event->getEntity();;
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