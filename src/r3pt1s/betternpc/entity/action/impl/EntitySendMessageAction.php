<?php

namespace r3pt1s\betternpc\entity\action\impl;

use pocketmine\nbt\tag\StringTag;
use pocketmine\player\Player;
use r3pt1s\betternpc\entity\action\EntityActionIds;
use r3pt1s\betternpc\entity\action\IEntityAction;
use r3pt1s\betternpc\entity\BetterEntity;
use pocketmine\nbt\tag\CompoundTag;

final class EntitySendMessageAction implements IEntityAction {

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

    public function getId(): int {
        return EntityActionIds::ACTION_SEND_MESSAGE;
    }

    public function toNbt(): CompoundTag {
        return CompoundTag::create()
            ->setString("message", $this->message);
    }

    public static function fromNbt(CompoundTag $nbt): ?EntitySendMessageAction {
        if ($nbt->getTag("message") instanceof StringTag) {
            return new self($nbt->getString("message"));
        }
        return null;
    }

    public static function create(string $message): self {
        return new self($message);
    }
}