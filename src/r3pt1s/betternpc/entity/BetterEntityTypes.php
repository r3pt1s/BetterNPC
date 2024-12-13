<?php

namespace r3pt1s\betternpc\entity;

use pocketmine\data\SavedDataLoadingException;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\world\World;
use r3pt1s\betternpc\entity\data\BetterEntityData;
use r3pt1s\betternpc\entity\impl\BetterHuman;
use r3pt1s\betternpc\entity\impl\BetterVillager;
use r3pt1s\betternpc\entity\impl\BetterZombie;

final class BetterEntityTypes {

    public const TYPE_HUMAN = "betterEntity:human";
    public const TYPE_ZOMBIE = "betterEntity:zombie";
    public const TYPE_VILLAGER = "betterEntity:villager";

    private static array $classes = [];

    public static function init(): void {
        self::register(BetterEntityTypes::TYPE_HUMAN, BetterHuman::class);
        self::register(BetterEntityTypes::TYPE_VILLAGER, BetterVillager::class);
        self::register(BetterEntityTypes::TYPE_ZOMBIE, BetterZombie::class);

        EntityFactory::getInstance()->register(BetterHuman::class, function (World $world, CompoundTag $nbt): BetterHuman {
            return new BetterHuman(self::parseEntityData($nbt), EntityDataHelper::parseLocation($nbt, $world), $nbt);
        }, [self::TYPE_HUMAN]);

        EntityFactory::getInstance()->register(BetterVillager::class, function (World $world, CompoundTag $nbt): BetterVillager {
            return new BetterVillager(self::parseEntityData($nbt), EntityDataHelper::parseLocation($nbt, $world), $nbt);
        }, [self::TYPE_VILLAGER]);

        EntityFactory::getInstance()->register(BetterZombie::class, function (World $world, CompoundTag $nbt): BetterZombie {
            return new BetterZombie(self::parseEntityData($nbt), EntityDataHelper::parseLocation($nbt, $world), $nbt);
        }, [self::TYPE_ZOMBIE]);
    }

    private static function parseEntityData(CompoundTag $nbt): BetterEntityData {
        if ($nbt->getTag("entityData") instanceof CompoundTag) {
            if (($data = BetterEntityData::fromNbt($nbt->getCompoundTag("entityData"))) === null) throw new SavedDataLoadingException("Failed to parse entity data, the entity data provided is broken");
            return $data;
        }
        throw new SavedDataLoadingException("Failed to parse entity data, the tag 'entityData' either does not exist or is not a compoundTag");
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

    public static function checkClass(string $class): bool {
        return in_array($class, self::$classes);
    }
}