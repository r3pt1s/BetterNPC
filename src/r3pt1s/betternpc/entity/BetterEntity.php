<?php

namespace r3pt1s\betternpc\entity;

use pocketmine\entity\Location;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;
use r3pt1s\betternpc\entity\animation\EntityAnimation;
use r3pt1s\betternpc\entity\data\BetterEntityData;

interface BetterEntity {

    public function __construct(BetterEntityData $entityData, Location $location, ?CompoundTag $nbt = null);

    public function runCommands(?Player $player): void;

    public function addCommand(string $command): void;

    public function removeCommand(string $command): void;

    public function checkCommand(string $command): bool;

    public function getCommands(): array;

    public function doEmote(?string $emoteId = null): void;

    public function addEmote(string $emoteId): void;

    public function removeEmote(string $emoteId): void;

    public function checkEmote(string $emoteId): bool;

    public function setEmotingEnabled(bool $emotingEnabled): void;

    public function getEmotes(): array;

    public function isEmotingEnabled(): bool;

    public function doAnimation(string $animation): void;

    public function addAnimation(EntityAnimation $animation): void;

    public function removeAnimation(EntityAnimation $animation): void;

    public function checkAnimation(string $animation): bool;

    public function getAnimation(string $animation): ?EntityAnimation;

    public function getAnimations(): array;

    public function getEntityData(): BetterEntityData;

    public function isCompatible(BetterEntityData $entityData): bool;
}