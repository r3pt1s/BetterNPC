<?php

namespace r3pt1s\betternpc\command\sub;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use r3pt1s\betternpc\entity\BetterEntity;
use r3pt1s\betternpc\Main;
use r3pt1s\betternpc\player\PlayerSession;

final class EntityRemoveSubCommand extends BaseSubCommand {

    public function __construct() {
        parent::__construct(Main::getInstance(), "remove", "Remove an entity");
    }

    protected function prepare(): void {
        try {
            $this->registerArgument(0, new IntegerArgument("entityId", true));
        } catch (ArgumentOrderException $e) {
            Main::getInstance()->getLogger()->logException($e);
        }
    }

    /**
     * @param Player $sender
     * @param string $aliasUsed
     * @param array $args
     * @return void
     */
    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        $id = $args["entityId"] ?? null;
        if ($id === null && !($sender instanceof Player)) {
            $sender->sendMessage(Main::PREFIX . "§c/betternpc remove <entityId> §8- §7Remove an entity");
            return;
        }

        if ($id !== null) {
            $entity = Server::getInstance()->getWorldManager()->findEntity($id);
            if ($entity instanceof BetterEntity) {
                $entity->flagForDespawn();
                $sender->sendMessage(Main::PREFIX . "§aSuccessfully §cremoved §7the entity!");
            } else $sender->sendMessage(Main::PREFIX . "The entity you provided is §cNOT §7an entity created by this plugin!");
            return;
        }

        $session = PlayerSession::get($sender);
        if ($sender instanceof Player) {
            if ($session->isTryingToEdit()) {
                $sender->sendMessage(Main::PREFIX . "You are §calready §7trying to §eedit §7an entity! Cancel that process first to §cremove §7an entity!");
                return;
            }

            if ($session->isTryingToRemove()) {
                $session->setTryingToRemove(false);
                $sender->sendMessage(Main::PREFIX . "You §ccancelled §7the process of finding an entity!");
                return;
            }

            $session->setTryingToRemove(true);
            $sender->sendMessage(Main::PREFIX . "Hit an entity to §cremove §7it.");
        }
    }
}