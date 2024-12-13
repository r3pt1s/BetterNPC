<?php

namespace r3pt1s\betternpc\entity\data;

use pocketmine\entity\Entity;
use pocketmine\entity\Location;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use r3pt1s\betternpc\entity\action\EntityActionIds;
use r3pt1s\betternpc\entity\action\IEntityAction;
use r3pt1s\betternpc\entity\BetterEntity;
use r3pt1s\betternpc\entity\BetterEntityTypes;
use r3pt1s\betternpc\entity\model\SkinModel;

final class BetterEntityData {

    /**
     * @param string $type
     * @param string $nameTag
     * @param string $scoreTag
     * @param float $scale
     * @param IEntityAction|null $hitAction
     * @param SkinModel|null $skinModel
     */
    public function __construct(
        private readonly string $type,
        private string $nameTag,
        private string $scoreTag,
        private float $scale,
        private readonly BetterEntitySettings $settings,
        private ?IEntityAction $hitAction,
        private ?SkinModel $skinModel
    ) {}

    /**
     * @param Location $location
     * @param CompoundTag|null $nbt
     * @return BetterEntity&Entity|null
     */
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

    public function getSettings(): BetterEntitySettings {
        return $this->settings;
    }

    public function setHitAction(?IEntityAction $hitAction): void {
        $this->hitAction = $hitAction;
    }

    public function getHitAction(): ?IEntityAction {
        return $this->hitAction;
    }

    public function setSkinModel(?SkinModel $skinModel): void {
        $this->skinModel = $skinModel;
    }

    public function getSkinModel(): ?SkinModel {
        return $this->skinModel;
    }

    public function toNbt(): CompoundTag {
        return CompoundTag::create()
            ->setString("type", $this->type)
            ->setString("nameTag", $this->nameTag)
            ->setString("scoreTag", $this->scoreTag)
            ->setFloat("scale", $this->scale)
            ->setTag("settings", $this->settings->toNbt())
            ->setInt("hitActionId", $this->hitAction?->getId() ?? -1)
            ->setTag("hitAction", $this->hitAction?->toNbt() ?? CompoundTag::create())
            ->setTag("skinModel", $this->skinModel?->toNbt() ?? CompoundTag::create());
    }

    public static function fromNbt(CompoundTag $nbt): ?self {
        if (
            $nbt->getTag("type") instanceof StringTag &&
            $nbt->getTag("nameTag") instanceof StringTag &&
            $nbt->getTag("scoreTag") instanceof StringTag &&
            $nbt->getTag("scale") instanceof FloatTag &&
            $nbt->getTag("settings") instanceof CompoundTag &&
            $nbt->getTag("hitActionId") instanceof IntTag &&
            $nbt->getTag("hitAction") instanceof CompoundTag &&
            $nbt->getTag("skinModel") instanceof CompoundTag
        ) {
            $settings = BetterEntitySettings::fromNbt($nbt->getCompoundTag("settings"));
            $hitAction = EntityActionIds::fromId($nbt->getInt("hitActionId"), $nbt->getCompoundTag("hitAction"));
            $skinModel = SkinModel::fromNbt($nbt->getCompoundTag("skinModel"));

            return new self(
                $nbt->getString("type"),
                $nbt->getString("nameTag"),
                $nbt->getString("scoreTag"),
                $nbt->getFloat("scale"),
                $settings,
                $hitAction,
                $skinModel
            );
        }
        return null;
    }

    public static function create(
        string $type,
        string $nameTag,
        string $scoreTag,
        float $scale,
        BetterEntitySettings $settings,
        ?IEntityAction $hitAction,
        ?SkinModel $skinModel
    ): self {
        return new self($type, $nameTag, $scoreTag, $scale, $settings, $hitAction, $skinModel);
    }
}