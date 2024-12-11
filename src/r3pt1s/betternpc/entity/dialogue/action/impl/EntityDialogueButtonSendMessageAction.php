<?php

namespace r3pt1s\betternpc\entity\dialogue\action\impl;

use pocketmine\player\Player;
use r3pt1s\betternpc\entity\BetterEntity;
use r3pt1s\betternpc\entity\dialogue\action\EntityDialogueButtonActions;
use r3pt1s\betternpc\entity\dialogue\action\IEntityDialogueButtonAction;

final class EntityDialogueButtonSendMessageAction implements IEntityDialogueButtonAction {

    public function __construct(private string $message) {}

    public function doAction(Player $player, BetterEntity $entity): void {
        $player->sendMessage($this->message);
    }

    public function setMessage(string $message): void {
        $this->message = $message;
    }

    public function getMessage(): string {
        return $this->message;
    }

    public function getData(): array {
        return ["message" => $this->message];
    }

    public function getId(): int {
        return EntityDialogueButtonActions::ACTION_SEND_MESSAGE;
    }

    public static function fromData(array $data): ?static {
        return null;
    }
}