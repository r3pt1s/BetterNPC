<?php

namespace r3pt1s\betternpc\command\sub;

use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use r3pt1s\betternpc\form\FormManager;
use r3pt1s\betternpc\Main;

final class EntityCreateSubCommand extends BaseSubCommand {

    public function __construct() {
        parent::__construct(Main::getInstance(), "create", "Create a new entity");
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
        $sender->sendForm(FormManager::createEntityForm());
    }
}