<?php

namespace r3pt1s\betternpc\command;

use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use r3pt1s\betternpc\command\sub\EntityCreateSubCommand;
use r3pt1s\betternpc\command\sub\EntityEditSubCommand;
use r3pt1s\betternpc\command\sub\EntityListSubCommand;
use r3pt1s\betternpc\command\sub\EntityRemoveSubCommand;
use r3pt1s\betternpc\Main;

final class EntityMainCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(Main::getInstance(), "betternpc", "BetterNPC Main Command", ["npc"]);
    }

    protected function prepare(): void {
        $this->setPermission("betternpc.command");
        $this->addConstraint(new InGameRequiredConstraint($this));

        $this->registerSubCommand(new EntityCreateSubCommand());
        $this->registerSubCommand(new EntityRemoveSubCommand());
        $this->registerSubCommand(new EntityEditSubCommand());
        $this->registerSubCommand(new EntityListSubCommand());
    }

    /**
     * @param Player $sender
     * @param string $aliasUsed
     * @param array $args
     * @return void
     */
    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        $sender->sendMessage(Main::PREFIX . "§c/betternpc create §8- §7Create an entity");
        $sender->sendMessage(Main::PREFIX . "§c/betternpc remove §8- §7Remove an entity");
        $sender->sendMessage(Main::PREFIX . "§c/betternpc edit §8- §7Edit an entity");
        $sender->sendMessage(Main::PREFIX . "§c/betternpc list §8- §7List the created entities");
    }
}