<?php

namespace r3pt1s\betternpc\entity\animation;

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\mcpe\protocol\AnimateEntityPacket;
use r3pt1s\betternpc\entity\BetterEntity;

final class EntityAnimation {

    public function __construct(
        private readonly string $animation,
        private string $nextState,
        private string $stopExpression,
        private int $stopExpressionVersion,
        private string $controller,
        private float $blendOutTime
    ) {}

    public function doAnimation(BetterEntity $entity): void {
        $packet = AnimateEntityPacket::create(
            $this->animation,
            $this->nextState,
            $this->stopExpression,
            $this->stopExpressionVersion,
            $this->controller,
            $this->blendOutTime,
            [$entity->getId()]
        );

        foreach ($entity->getViewers() as $viewer) {
            $viewer->getNetworkSession()->sendDataPacket($packet);
        }
    }

    public function getAnimation(): string {
        return $this->animation;
    }

    public function getNextState(): string {
        return $this->nextState;
    }

    public function setNextState(string $nextState): void {
        $this->nextState = $nextState;
    }

    public function getStopExpression(): string {
        return $this->stopExpression;
    }

    public function setStopExpression(string $stopExpression): void {
        $this->stopExpression = $stopExpression;
    }

    public function getStopExpressionVersion(): int {
        return $this->stopExpressionVersion;
    }

    public function setStopExpressionVersion(int $stopExpressionVersion): void {
        $this->stopExpressionVersion = $stopExpressionVersion;
    }

    public function getController(): string {
        return $this->controller;
    }

    public function setController(string $controller): void {
        $this->controller = $controller;
    }

    public function getBlendOutTime(): float {
        return $this->blendOutTime;
    }

    public function setBlendOutTime(float $blendOutTime): void {
        $this->blendOutTime = $blendOutTime;
    }

    public function toNbt(): CompoundTag {
        return CompoundTag::create()
            ->setString("animation", $this->animation)
            ->setString("nextState", $this->nextState)
            ->setString("stopExpression", $this->stopExpression)
            ->setInt("stopExpressionVersion", $this->stopExpressionVersion)
            ->setString("controller", $this->controller)
            ->setFloat("blendOutTime", $this->blendOutTime);
    }

    public static function fromNbt(CompoundTag $nbt): ?EntityAnimation {
        if (
            $nbt->getTag("animation") instanceof StringTag &&
            $nbt->getTag("nextState") instanceof StringTag &&
            $nbt->getTag("stopExpression") instanceof StringTag &&
            $nbt->getTag("stopExpressionVersion") instanceof IntTag &&
            $nbt->getTag("controller") instanceof StringTag &&
            $nbt->getTag("blendOutTime") instanceof FloatTag
        ) {
            return new self(
                $nbt->getString("animation"),
                $nbt->getString("nextState"),
                $nbt->getString("stopExpression"),
                $nbt->getInt("stopExpressionVersion"),
                $nbt->getString("controller"),
                $nbt->getFloat("blendOutTime")
            );
        }
        return null;
    }
}