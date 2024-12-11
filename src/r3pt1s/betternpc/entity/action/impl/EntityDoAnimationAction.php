<?php

namespace r3pt1s\betternpc\entity\action\impl;

use pocketmine\player\Player;
use r3pt1s\betternpc\entity\action\EntityActionIds;
use r3pt1s\betternpc\entity\action\IEntityAction;
use r3pt1s\betternpc\entity\BetterEntity;

final class EntityDoAnimationAction implements IEntityAction {

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
        return EntityActionIds::ACTION_SEND_MESSAGE;
    }

    public static function fromData(array $data): ?static {
        return null;
    }
}