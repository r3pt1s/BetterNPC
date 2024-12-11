<?php

namespace r3pt1s\betternpc\entity\trait;

use r3pt1s\betternpc\entity\animation\EntityAnimation;

trait AnimationTrait {

    /** @var array<EntityAnimation> */
    protected array $animations = [];

    public function doAnimation(string $animation): void {
        $this->animations[$animation]?->doAnimation($this);
    }

    public function addAnimation(EntityAnimation $animation): void {
        if (!$this->checkAnimation($animation->getAnimation())) $this->animations[$animation->getAnimation()] = $animation;
    }

    public function removeAnimation(EntityAnimation $animation): void {
        if (!$this->checkAnimation($animation->getAnimation())) unset($this->animations[$animation->getAnimation()]);
    }

    public function checkAnimation(string $animation): bool {
        return isset($this->animations[$animation]);
    }

    public function getAnimation(string $animation): ?EntityAnimation {
        return $this->animations[$animation] ?? null;
    }

    public function getAnimations(): array {
        return $this->animations;
    }
}