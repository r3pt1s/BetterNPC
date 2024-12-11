<?php

namespace r3pt1s\betternpc\entity\dialogue\action\impl;

use pocketmine\player\Player;
use r3pt1s\betternpc\entity\BetterEntity;
use r3pt1s\betternpc\entity\dialogue\action\EntityDialogueButtonActions;
use r3pt1s\betternpc\entity\dialogue\action\IEntityDialogueButtonAction;

final class EntityDialogueButtonDoAnimationAction implements IEntityDialogueButtonAction {

    public function __construct(private string $animation) {}

    public function doAction(Player $player, BetterEntity $entity): void {
        $entity->doAnimation($this->animation);
    }

    public function setAnimation(string $animation): void {
        $this->animation = $animation;
    }

    public function getAnimation(): string {
        return $this->animation;
    }

    public function getData(): array {
        return ["animation" => $this->animation];
    }

    public function getId(): int {
        return EntityDialogueButtonActions::ACTION_SEND_MESSAGE;
    }

    public static function fromData(array $data): ?static {
        return null;
    }
}