<?php

namespace r3pt1s\betternpc\entity\trait;

use pocketmine\entity\Human;
use r3pt1s\betternpc\entity\list\EmoteList;

trait EmoteTrait {

    private bool $emotingEnabled = true;
    protected array $emotes = [];

    public function doEmote(?string $emoteId = null): void {
        if ($this->emotingEnabled && $this instanceof Human) {
            $emote = $emoteId ?? (empty($this->emotes) ? EmoteList::randomEmote() : $this->emotes[array_rand($this->emotes)]);
            foreach ($this->getViewers() as $viewer) {
                $viewer->getNetworkSession()->getEntityEventBroadcaster()->onEmote([$viewer], $this, $emote);
            }
        }
    }

    public function addEmote(string $emoteId): void {
        if (!$this->checkEmote($emoteId)) $this->emotes[] = $emoteId;
    }

    public function removeEmote(string $emoteId): void {
        if ($this->checkEmote($emoteId)) unset($this->emotes[array_search($emoteId, $this->emotes)]);
    }

    public function checkEmote(string $emoteId): bool {
        return in_array($emoteId, $this->emotes);
    }

    public function setEmotingEnabled(bool $emotingEnabled): void {
        $this->emotingEnabled = $emotingEnabled;
    }

    public function getEmotes(): array {
        return $this->emotes;
    }

    public function isEmotingEnabled(): bool {
        return $this->emotingEnabled;
    }
}