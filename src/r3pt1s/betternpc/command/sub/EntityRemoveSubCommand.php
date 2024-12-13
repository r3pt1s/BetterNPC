<?php

namespace r3pt1s\betternpc\command\sub;

use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use r3pt1s\betternpc\Main;
use r3pt1s\betternpc\player\PlayerSession;

final class EntityRemoveSubCommand extends BaseSubCommand {

    public function __construct() {
        parent::__construct(Main::getInstance(), "remove", "Remove an entity");
    }

    protected function prepare(): void {
        $this->addConstraint(new InGameRequiredConstraint($this));
    }

    /**
     * @param Player $sender
     * @param string $aliasUsed
     * @param array $args
     * @return void
     */
    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        $session = PlayerSession::get($sender);

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