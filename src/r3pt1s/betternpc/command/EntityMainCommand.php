<?php

namespace r3pt1s\betternpc\command;

use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use r3pt1s\betternpc\entity\action\impl\EntityDoEmoteAction;
use r3pt1s\betternpc\entity\BetterEntityTypes;
use r3pt1s\betternpc\entity\data\BetterEntityData;
use r3pt1s\betternpc\entity\list\EmoteList;
use r3pt1s\betternpc\entity\model\SkinModel;
use r3pt1s\betternpc\Main;

final class EntityMainCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(Main::getInstance(), "betternpc", "BetterNPC Main Command");
    }

    protected function prepare(): void {
        $this->setPermission("betternpc.command");
        $this->addConstraint(new InGameRequiredConstraint($this));

    }

    /**
     * @param Player $sender
     * @param string $aliasUsed
     * @param array $args
     * @return void
     */
    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {
        $entity = BetterEntityData::create(
            BetterEntityTypes::TYPE_HUMAN,
            "BetterNPC (Human)",
            "join lydoxmc.net",
            2.0,
            EntityDoEmoteAction::create(EmoteList::randomEmote()),
            SkinModel::fromSkin("skinmode.first", $sender->getSkin())
        );
        $entity->buildEntity($sender->getLocation())->spawnToAll();
    }
}