<?php

namespace r3pt1s\betternpc\entity;

use r3pt1s\betternpc\entity\impl\BetterHuman;

final class BetterEntityTypes {

    public const TYPE_HUMAN = "betterEntity:human";
    public const TYPE_ZOMBIE = "betterEntity:zombie";
    public const TYPE_VILLAGER = "betterEntity:villager";

    private static array $classes = [];

    public static function init(): void {
        self::register(BetterEntityTypes::TYPE_HUMAN, BetterHuman::class);
    }

    public static function register(string $id, string $class): void {
        if (!is_subclass_of($class, BetterEntity::class)) return;
        self::$classes[$id] = $class;
    }

    public static function unregister(string $id): void {
        unset(self::$classes[$id]);
    }

    public static function get(string $id): ?string {
        return self::$classes[$id] ?? null;
    }

    public static function check(string $id): bool {
        return isset(self::$classes[$id]);
    }
}