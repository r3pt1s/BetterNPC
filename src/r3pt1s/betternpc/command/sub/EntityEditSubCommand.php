<?php

namespace r3pt1s\betternpc\command\sub;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use r3pt1s\betternpc\entity\BetterEntity;
use r3pt1s\betternpc\form\FormManager;
use r3pt1s\betternpc\Main;
use r3pt1s\betternpc\player\PlayerSession;

final class EntityEditSubCommand extends BaseSubCommand {

    public function __construct() {
        parent::__construct("edit", "Edit an entity");
    }

    protected function prepare(): void {
        $this->addConstraint(new InGameRequiredConstraint($this));
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
        $session = PlayerSession::get($sender);
        $entityId = $args["entityId"] ?? null;
        if ($entityId !== null) {
            $entity = Server::getInstance()->getWorldManager()->findEntity($entityId);
            if ($entity instanceof BetterEntity) {
                $sender->sendForm(FormManager::editEntityForm($entity));
            } else $sender->sendMessage(Main::PREFIX . "§cThe entityId you provided was not a §8'§bBetterEntity§8'§c!");
            return;
        }

        if ($session->isTryingToRemove()) {
            $sender->sendMessage(Main::PREFIX . "You are §calready §7trying to §cremove §7an entity! Cancel that process first to §cremove §7an entity!");
            return;
        }

        if ($session->isTryingToEdit()) {
            $session->setTryingToEdit(false);
            $sender->sendMessage(Main::PREFIX . "You §ccancelled §7the process of finding an entity!");
            return;
        }

        $session->setTryingToEdit(true);
        $sender->sendMessage(Main::PREFIX . "Hit an entity to §eedit §7it.");
    }
}