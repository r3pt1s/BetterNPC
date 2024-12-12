<?php

namespace r3pt1s\betternpc\entity\action\impl;

use pocketmine\nbt\tag\StringTag;
use pocketmine\player\Player;
use r3pt1s\betternpc\entity\action\EntityActionIds;
use r3pt1s\betternpc\entity\action\IEntityAction;
use r3pt1s\betternpc\entity\BetterEntity;
use pocketmine\nbt\tag\CompoundTag;

final class EntityOpenDialogueAction implements IEntityAction {

    public function __construct(private string $dialogueId) {}

    public function doAction(Player $player, BetterEntity $entity): void {
        if (($dialogue = $entity->getEntityData()->getDialogue($this->dialogueId)) !== null) {

        }
    }

    public function setDialogueId(string $dialogueId): void {
        $this->dialogueId = $dialogueId;
    }

    public function getDialogueId(): string {
        return $this->dialogueId;
    }

    public function getId(): int {
        return EntityActionIds::ACTION_OPEN_DIALOGUE;
    }

    public function toNbt(): CompoundTag {
        return CompoundTag::create()
            ->setString("dialogueId", $this->dialogueId);
    }

    public static function fromNbt(CompoundTag $nbt): ?EntityOpenDialogueAction {
        if ($nbt->getTag("dialogueId") instanceof StringTag) {
            return new self($nbt->getString("dialogueId"));
        }
        return null;
    }
}