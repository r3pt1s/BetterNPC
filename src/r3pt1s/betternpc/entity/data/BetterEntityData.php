<?php

namespace r3pt1s\betternpc\entity\data;

use pocketmine\entity\Location;
use pocketmine\nbt\tag\CompoundTag;
use r3pt1s\betternpc\entity\action\IEntityAction;
use r3pt1s\betternpc\entity\BetterEntity;
use r3pt1s\betternpc\entity\BetterEntityTypes;
use r3pt1s\betternpc\entity\dialogue\EntityDialogue;
use r3pt1s\betternpc\entity\model\SkinModel;

final class BetterEntityData {

    public function __construct(
        private readonly string $type,
        private string $nameTag,
        private string $scoreTag,
        private float $scale,
        private ?IEntityAction $onHitAction,
        private array $dialogues,
        private ?SkinModel $skinModel,
    ) {}

    public function buildEntity(Location $location, ?CompoundTag $nbt = null): ?BetterEntity {
        $class = BetterEntityTypes::get($this->type);
        if ($class === null || !method_exists($class, "isCompatible")) return null;
        if (!$class::isCompatible($this)) return null;
        return new $class($this, $location, $nbt);
    }

    public function getType(): string {
        return $this->type;
    }

    public function setNameTag(string $nameTag): void {
        $this->nameTag = $nameTag;
    }

    public function getNameTag(): string {
        return $this->nameTag;
    }

    public function setScoreTag(string $scoreTag): void {
        $this->scoreTag = $scoreTag;
    }

    public function getScoreTag(): string {
        return $this->scoreTag;
    }

    public function setScale(float $scale): void {
        $this->scale = $scale;
    }

    public function getScale(): float {
        return $this->scale;
    }

    public function setOnHitAction(?IEntityAction $onHitAction): void {
        $this->onHitAction = $onHitAction;
    }

    public function getOnHitAction(): ?IEntityAction {
        return $this->onHitAction;
    }

    public function addDialogue(EntityDialogue $dialogue): void {
        if (!$this->checkDialogue($dialogue->getId())) $this->dialogues[$dialogue->getId()] = $dialogue;
    }

    public function removeDialogue(EntityDialogue $dialogue): void {
        if ($this->checkDialogue($dialogue->getId())) unset($this->dialogues[$dialogue->getId()]);
    }

    public function getDialogues(): array {
        return $this->dialogues;
    }

    public function checkDialogue(string $id): bool {
        return isset($this->dialogues[$id]);
    }

    public function setSkinModel(?SkinModel $skinModel): void {
        $this->skinModel = $skinModel;
    }

    public function getSkinModel(): ?SkinModel {
        return $this->skinModel;
    }

    public static function fromData(array $data): ?self {
        return null;
    }
}