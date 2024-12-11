<?php

namespace r3pt1s\betternpc\entity\action;

use pocketmine\player\Player;
use r3pt1s\betternpc\entity\BetterEntity;

interface IEntityAction {

    public function doAction(Player $player, BetterEntity $entity): void;

    public function getData(): array;

    public function getId(): int;

    public static function fromData(array $data): ?static;
}