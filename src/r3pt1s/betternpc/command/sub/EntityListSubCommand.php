<?php

namespace r3pt1s\betternpc\command\sub;

use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use r3pt1s\betternpc\entity\BetterEntity;
use r3pt1s\betternpc\Main;

final class EntityListSubCommand extends BaseSubCommand {

    public function __construct() {
        parent::__construct(Main::getInstance(), "list", "List the created entities");
    }

    protected function prepare(): void {}

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
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
                $entity->getPosition()->getFloorZ()
            );
        }
    }
}