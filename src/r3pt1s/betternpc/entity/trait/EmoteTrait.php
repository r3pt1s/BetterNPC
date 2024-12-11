<?php

namespace r3pt1s\betternpc\entity\trait;

use r3pt1s\betternpc\entity\list\EmoteList;

trait EmoteTrait {

    private bool $emotingEnabled = true;
    protected array $emotes = [];

    public function doEmote(): void {
        if ($this->emotingEnabled) {
            $emote = empty($this->emotes) ? EmoteList::randomEmote() : $this->emotes[array_rand($this->emotes)];
            //TODO: entity emote
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