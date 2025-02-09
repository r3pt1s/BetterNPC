<?php

namespace r3pt1s\betternpc\entity\util;

use pocketmine\utils\Config;
use r3pt1s\betternpc\Main;

final class EmoteList {

    public const DEFAULT = [
        "wave" => [
            "id" => "4c8ae710-df2e-47cd-814d-cc7bf21a3d67",
            "name" => "Wave"
        ],
        "yoga" => [
            "id" => "3f1bdf46-80b0-4a64-b631-4ac2f2491165",
            "name" => "Yoga"
        ]
    ];

    private static array $emotes = [];

    public static function loadEmotes(Config $config): void {
        foreach ($config->get("emotes", self::DEFAULT) as $key => $data) {
            if (is_array($data) && isset($data["id"], $data["name"])) {
                self::$emotes[$key] = $data;
            } else Main::getInstance()->getLogger()->warning("Failed to load emote $key: No data available");
        }
    }

    public static function randomEmote(): array {
        $emotes = self::getEmotes();
        return $emotes[array_rand($emotes)];
    }

    public static function getEmotes(): array {
        return self::$emotes;
    }

    public static function getEmoteNames(bool $sort = true): array {
        $emotes = array_values(array_map(fn(array $emote) => $emote["name"], self::getEmotes()));
        if ($sort) sort($emotes);

        return $emotes;
    }

    public static function getEmoteByName(string $name): ?array {
        foreach (self::getEmotes() as $emote) {
            if ($emote["name"] == $name) return $emote;
        }

        return null;
    }

    public static function getEmoteIdByName(string $name): ?string {
        foreach (self::getEmotes() as $emote) {
            if ($emote["name"] == $name) return $emote["id"];
        }

        return null;
    }

    public static function getNameById(string $id): ?string {
        foreach (self::getEmotes() as $emote) {
            if ($emote["id"] == $id) return $emote["name"];
        }

        return null;
    }
}