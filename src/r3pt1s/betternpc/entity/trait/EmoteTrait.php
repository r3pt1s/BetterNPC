<?php

namespace r3pt1s\betternpc\entity\trait;

use pocketmine\entity\Human;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Server;
use r3pt1s\betternpc\entity\util\EmoteList;
use r3pt1s\betternpc\entity\util\EntityGlobalSettings;

trait EmoteTrait {

    private bool $emotingEnabled = true;
    private int $nextEmote = 0;
    protected array $emotes = [];

    public function loadEmotes(?ListTag $nbt): void {
        $this->emotes = $this->convertNbtToEmotes($nbt);
    }

    public function emoteTick(): void {
        if (!$this->getEntityData()->getSettings()->isDoRandomEmotes()) return;
        if ($this->emotingEnabled && Server::getInstance()->getTick() >= $this->nextEmote) {
            $this->nextEmote = Server::getInstance()->getTick() + (EntityGlobalSettings::getIntSetting(EntityGlobalSettings::KEY_RANDOM_EMOTE_COOLDOWN) * 20);
            $this->doEmote();
        }
    }

    public function doEmote(?string $emoteId = null): void {
        if ($this->emotingEnabled && $this instanceof Human) {
            $emote = $emoteId ?? (empty($this->emotes) ? EmoteList::randomEmote()["id"] : $this->emotes[array_rand($this->emotes)]);
            foreach ($this->getViewers() as $viewer) {
                $viewer->getNetworkSession()->getEntityEventBroadcaster()->onEmote([$viewer->getNetworkSession()], $this, $emote);
            }
        }
    }

    public function addEmote(string $emoteId): void {
        if (!$this->checkEmote($emoteId)) $this->emotes[] = $emoteId;
    }

    public function removeEmote(string $emoteId): void {
        if ($this->checkEmote($emoteId)) {
            unset($this->emotes[array_search($emoteId, $this->emotes)]);
            $this->emotes = array_values($this->emotes);
        }
    }

    public function checkEmote(string $emoteId): bool {
        return in_array($emoteId, $this->emotes);
    }

    public function setEmotingEnabled(bool $emotingEnabled): void {
        $this->emotingEnabled = $emotingEnabled;
    }

    public function convertEmotesToNbt(): ListTag {
        return new ListTag(array_map(fn(string $emote) => new StringTag($emote), $this->emotes));
    }

    public function getEmotes(): array {
        return $this->emotes;
    }

    public function isEmotingEnabled(): bool {
        return $this->emotingEnabled;
    }

    private function convertNbtToEmotes(?ListTag $nbt): array {
        if (!$nbt instanceof ListTag) return [];
        $emotes = [];
        foreach ($nbt->getValue() as $tag) {
            if ($tag instanceof StringTag) $emotes[] = $tag->getValue();
        }

        return $emotes;
    }
}