<?php

namespace r3pt1s\betternpc\entity\dialogue\action\impl;

use pocketmine\player\Player;
use r3pt1s\betternpc\entity\BetterEntity;
use r3pt1s\betternpc\entity\dialogue\action\EntityDialogueButtonActions;
use r3pt1s\betternpc\entity\dialogue\action\IEntityDialogueButtonAction;
use r3pt1s\betternpc\entity\dialogue\EntityDialogue;

final class EntityDialogueButtonOpenDialogueAction implements IEntityDialogueButtonAction {

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
        return EntityDialogueButtonActions::ACTION_OPEN_DIALOGUE;
    }

    public static function fromData(array $data): ?static {
        return null;
    }
}