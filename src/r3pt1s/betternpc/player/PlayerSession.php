<?php

namespace r3pt1s\betternpc\player;

use pocketmine\player\Player;
use pocketmine\Server;

final class PlayerSession {

    private bool $tryingToEdit = false;
    private bool $tryingToRemove = false;
    private int $coolDown = 0;

    public function __construct(private readonly string $name) {}

    public function setCoolDown(int $ticks): void {
        $this->coolDown = Server::getInstance()->getTick() + $ticks;
    }

    public function isTryingToEdit(): bool {
        return $this->tryingToEdit;
    }

    public function setTryingToEdit(bool $tryingToEdit): void {
        $this->tryingToEdit = $tryingToEdit;
    }

    public function isTryingToRemove(): bool {
        return $this->tryingToRemove;
    }

    public function setTryingToRemove(bool $tryingToRemove): void {
        $this->tryingToRemove = $tryingToRemove;
    }

    public function checkCoolDown(): bool {
        return Server::getInstance()->getTick() < $this->coolDown;
    }

    public function getPlayer(): ?Player {
        return Server::getInstance()->getPlayerExact($this->name);
    }

    public function getName(): string {
        return $this->name;
    }

    public static function get(Player $player): ?PlayerSession {
        return PlayerSessionCache::get($player);
    }
}