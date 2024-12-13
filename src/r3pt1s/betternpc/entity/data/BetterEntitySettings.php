<?php

namespace r3pt1s\betternpc\entity\data;

use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;

final class BetterEntitySettings {

    public function __construct(
        private bool $nameTagAlwaysVisible,
        private bool $lookToPlayers,
        private bool $doRandomEmotes
    ) {}

    public function isNameTagAlwaysVisible(): bool {
        return $this->nameTagAlwaysVisible;
    }

    public function setNameTagAlwaysVisible(bool $nameTagAlwaysVisible): void {
        $this->nameTagAlwaysVisible = $nameTagAlwaysVisible;
    }

    public function isLookToPlayers(): bool {
        return $this->lookToPlayers;
    }

    public function setLookToPlayers(bool $lookToPlayers): void {
        $this->lookToPlayers = $lookToPlayers;
    }

    public function isDoRandomEmotes(): bool {
        return $this->doRandomEmotes;
    }

    public function setDoRandomEmotes(bool $doRandomEmotes): void {
        $this->doRandomEmotes = $doRandomEmotes;
    }

    public function toNbt(): CompoundTag {
        return CompoundTag::create()
            ->setByte("nameTagAlwaysVisible", $this->nameTagAlwaysVisible)
            ->setByte("lookToPlayers", $this->lookToPlayers)
            ->setByte("doRandomEmotes", $this->doRandomEmotes);
    }

    public static function fromNbt(CompoundTag $nbt): ?BetterEntitySettings {
        if (
            $nbt->getTag("nameTagAlwaysVisible") instanceof ByteTag &&
            $nbt->getTag("lookToPlayers") instanceof ByteTag &&
            $nbt->getTag("doRandomEmotes") instanceof ByteTag
        ) {
            return new BetterEntitySettings((bool) $nbt->getByte("nameTagAlwaysVisible"), (bool) $nbt->getByte("lookToPlayers"), (bool) $nbt->getByte("doRandomEmotes"));
        }
        return null;
    }

    public static function create(bool $nameTagAlwaysVisible, bool $lookToPlayers, bool $doRandomEmotes): self {
        return new self($nameTagAlwaysVisible, $lookToPlayers, $doRandomEmotes);
    }
}