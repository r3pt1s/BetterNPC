<?php

namespace r3pt1s\betternpc\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use r3pt1s\betternpc\entity\BetterEntity;
use r3pt1s\betternpc\form\FormManager;
use r3pt1s\betternpc\Main;
use r3pt1s\betternpc\player\PlayerSession;

final class EntityMainCommand extends Command {

    public function __construct() {
        parent::__construct("betternpc", "BetterNPC Main Command", "/betternpc", ["npc"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if ($sender instanceof Player) {
            if (count($args) > 0) {
                if (!$this->handleSubCommand($sender, strtolower(array_shift($args)), $args)) {
                    $sender->sendMessage(Main::PREFIX . "§c/betternpc create §8- §7Create an entity");
                    $sender->sendMessage(Main::PREFIX . "§c/betternpc remove [entityId: int] §8- §7Remove an entity");
                    $sender->sendMessage(Main::PREFIX . "§c/betternpc edit [entityId: int] §8- §7Edit an entity");
                    $sender->sendMessage(Main::PREFIX . "§c/betternpc list §8- §7List the created entities");
                }
                return true;
            }

            $sender->sendMessage(Main::PREFIX . "§c/betternpc create §8- §7Create an entity");
            $sender->sendMessage(Main::PREFIX . "§c/betternpc remove [entityId: int] §8- §7Remove an entity");
            $sender->sendMessage(Main::PREFIX . "§c/betternpc edit [entityId: int] §8- §7Edit an entity");
            $sender->sendMessage(Main::PREFIX . "§c/betternpc list §8- §7List the created entities");
        }
        return true;
    }

    private function handleSubCommand(Player $sender, string $subCommand, array $args): bool {
        $session = PlayerSession::get($sender);
        switch ($subCommand) {
            case "create": {
                $sender->sendForm(FormManager::createEntityForm());
                break;
            }
            case "remove": {
                $entityId = $args[0] ?? null;

                if ($entityId !== null) {
                    if (!is_numeric($entityId)) return false;

                    $entity = Server::getInstance()->getWorldManager()->findEntity(intval($entityId));
                    if ($entity instanceof BetterEntity) {
                        $entity->flagForDespawn();
                        $sender->sendMessage(Main::PREFIX . "§aSuccessfully §cremoved §7the entity!");
                    } else $sender->sendMessage(Main::PREFIX . "The entity you provided is §cNOT §7an entity created by this plugin!");
                    return true;
                }

                if ($session->isTryingToEdit()) {
                    $sender->sendMessage(Main::PREFIX . "You are §calready §7trying to §eedit §7an entity! Cancel that process first to §cremove §7an entity!");
                    return true;
                }

                if ($session->isTryingToRemove()) {
                    $session->setTryingToRemove(false);
                    $sender->sendMessage(Main::PREFIX . "You §ccancelled §7the process of finding an entity!");
                    return true;
                }

                $session->setTryingToRemove(true);
                $sender->sendMessage(Main::PREFIX . "Hit an entity to §cremove §7it.");
                break;
            }
            case "edit": {
                $session = PlayerSession::get($sender);
                $entityId = $args[0] ?? null;
                if ($entityId !== null) {
                    if (!is_numeric($entityId)) return false;

                    $entity = Server::getInstance()->getWorldManager()->findEntity(intval($entityId));
                    if ($entity instanceof BetterEntity) {
                        $sender->sendForm(FormManager::editEntityForm($entity));
                    } else $sender->sendMessage(Main::PREFIX . "§cThe entityId you provided was not a §8'§bBetterEntity§8'§c!");
                    return true;
                }

                if ($session->isTryingToRemove()) {
                    $sender->sendMessage(Main::PREFIX . "You are §calready §7trying to §cremove §7an entity! Cancel that process first to §cremove §7an entity!");
                    return true;
                }

                if ($session->isTryingToEdit()) {
                    $session->setTryingToEdit(false);
                    $sender->sendMessage(Main::PREFIX . "You §ccancelled §7the process of finding an entity!");
                    return true;
                }

                $session->setTryingToEdit(true);
                $sender->sendMessage(Main::PREFIX . "Hit an entity to §eedit §7it.");
                break;
            }
            case "list": {
                $sender->sendMessage(Main::PREFIX . "Searching for entities...");
                $foundEntities = [];
                foreach (Server::getInstance()->getWorldManager()->getWorlds() as $world) {
                    foreach ($world->getEntities() as $entity) {
                        if ($entity instanceof BetterEntity) $foundEntities[] = $entity;
                    }
                }

                if (empty($foundEntities)) $sender->sendMessage(Main::PREFIX . "§cNo §7entities found.");

                foreach ($foundEntities as $entity) {
                    $sender->sendMessage(Main::PREFIX . "EntityId: §e" . $entity->getId() . " §8(§b" . basename($entity::class) . "§8) - §7Location: §e" .
                        $entity->getPosition()->getFloorX() . "§8:§e" .
                        $entity->getPosition()->getFloorY() . "§8:§e" .
                        $entity->getPosition()->getFloorZ() . "§8:§e" .
                        $entity->getPosition()->getWorld()->getFolderName()
                    );
                }
                break;
            }
        }

        return true;
    }
}