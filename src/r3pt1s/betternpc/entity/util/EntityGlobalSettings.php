<?php

namespace r3pt1s\betternpc\entity\util;

use pocketmine\utils\Config;

final class EntityGlobalSettings {

    public const KEY_HIT_ACTION_COOLDOWN = "hit-action-cooldown";
    public const KEY_RANDOM_EMOTE_COOLDOWN = "random-emote-cooldown";
    public const KEY_SERVER_COMMAND_HANDLING = "server-command-handling";

    private static array $settings = [];

    public static function loadSettings(Config $config): void {
        self::$settings[self::KEY_HIT_ACTION_COOLDOWN] = $config->get(self::KEY_HIT_ACTION_COOLDOWN, 10);
        self::$settings[self::KEY_RANDOM_EMOTE_COOLDOWN] = $config->get(self::KEY_RANDOM_EMOTE_COOLDOWN, 60);
        self::$settings[self::KEY_SERVER_COMMAND_HANDLING] = $config->get(self::KEY_SERVER_COMMAND_HANDLING);

        if (!is_int(self::$settings[self::KEY_HIT_ACTION_COOLDOWN]) || self::$settings[self::KEY_HIT_ACTION_COOLDOWN] < 0) self::$settings[self::KEY_HIT_ACTION_COOLDOWN] = 10;
        if (!is_int(self::$settings[self::KEY_RANDOM_EMOTE_COOLDOWN]) || self::$settings[self::KEY_RANDOM_EMOTE_COOLDOWN] < 0) self::$settings[self::KEY_RANDOM_EMOTE_COOLDOWN] = 60;
        if (!is_bool(self::$settings[self::KEY_SERVER_COMMAND_HANDLING])) self::$settings[self::KEY_SERVER_COMMAND_HANDLING] = false;
    }

    public static function getIntSetting(string $key, ?int $default = null): ?int {
        return self::$settings[$key] ?? $default;
    }

    public static function getBoolSetting(string $key, ?bool $default = null): ?bool {
        return self::$settings[$key] ?? $default;
    }

    public static function getSettings(): array {
        return self::$settings;
    }
}