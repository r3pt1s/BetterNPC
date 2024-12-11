<?php

namespace r3pt1s\betternpc\entity\action\impl;

use pocketmine\player\Player;
use r3pt1s\betternpc\entity\action\EntityActionIds;
use r3pt1s\betternpc\entity\action\IEntityAction;
use r3pt1s\betternpc\entity\BetterEntity;

final class EntityDoEmoteAction implements IEntityAction {

    public function __construct(private string $emoteId) {}

    public function doAction(Player $player, BetterEntity $entity): void {
        $entity->doEmote($this->emoteId);
    }

    public function setEmoteId(string $emoteId): void {
        $this->emoteId = $emoteId;
    }

    public function getEmoteId(): string {
        return $this->emoteId;
    }

    public function getData(): array {
        return ["emote" => $this->emoteId];
    }

    public function getId(): int {
        return EntityActionIds::ACTION_SEND_MESSAGE;
    }

    public static function fromData(array $data): ?static {
        return null;
    }
}