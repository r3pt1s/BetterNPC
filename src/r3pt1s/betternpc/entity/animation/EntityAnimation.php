<?php

namespace r3pt1s\betternpc\entity\animation;

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
}