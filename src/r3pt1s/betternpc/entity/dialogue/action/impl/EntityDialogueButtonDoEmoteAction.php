<?php

namespace r3pt1s\betternpc\entity\dialogue\action\impl;

use pocketmine\player\Player;
use r3pt1s\betternpc\entity\BetterEntity;
use r3pt1s\betternpc\entity\dialogue\action\EntityDialogueButtonActions;
use r3pt1s\betternpc\entity\dialogue\action\IEntityDialogueButtonAction;

final class EntityDialogueButtonDoEmoteAction implements IEntityDialogueButtonAction {

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
        return EntityDialogueButtonActions::ACTION_SEND_MESSAGE;
    }

    public static function fromData(array $data): ?static {
        return null;
    }
}