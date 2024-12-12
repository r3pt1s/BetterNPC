<?php

namespace r3pt1s\betternpc\entity\action\impl;

use pocketmine\nbt\tag\StringTag;
use pocketmine\player\Player;
use r3pt1s\betternpc\entity\action\EntityActionIds;
use r3pt1s\betternpc\entity\action\IEntityAction;
use r3pt1s\betternpc\entity\BetterEntity;
use pocketmine\nbt\tag\CompoundTag;

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

    public function getId(): int {
        return EntityActionIds::ACTION_DO_EMOTE;
    }

    public function toNbt(): CompoundTag {
        return CompoundTag::create()
            ->setString("emoteId", $this->emoteId);
    }

    public static function fromNbt(CompoundTag $nbt): ?EntityDoEmoteAction {
        if ($nbt->getTag("emoteId") instanceof StringTag) {
            return new self($nbt->getString("emoteId"));
        }
        return null;
    }
}