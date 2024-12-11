<?php

namespace r3pt1s\betternpc\entity\action\impl;

use pocketmine\player\Player;
use r3pt1s\betternpc\entity\action\EntityActionIds;
use r3pt1s\betternpc\entity\action\IEntityAction;
use r3pt1s\betternpc\entity\BetterEntity;
use r3pt1s\betternpc\entity\dialogue\EntityDialogue;

final class EntityOpenDialogueAction implements IEntityAction {

    public function __construct(private EntityDialogue $dialogue) {}

    public function doAction(Player $player, BetterEntity $entity): void {

    }

    public function setDialogue(EntityDialogue $dialogue): void {
        $this->dialogue = $dialogue;
    }

    public function getDialogue(): EntityDialogue {
        return $this->dialogue;
    }

    public function getData(): array {
        return ["dialogue" => $this->dialogue->getId()];
    }

    public function getId(): int {
        return EntityActionIds::ACTION_OPEN_DIALOGUE;
    }

    public static function fromData(array $data): ?static {
        return null;
    }
}