<?php

namespace r3pt1s\betternpc\player;

use pocketmine\player\Player;

final class PlayerSessionCache {

    private static array $sessions = [];

    public static function create(Player $player): PlayerSession {
        return self::$sessions[$player->getName()] ??= new PlayerSession($player);
    }

    public static function get(Player $player): ?PlayerSession {
        return self::$sessions[$player->getName()] ?? null;
    }

    public static function remove(Player $player): void {
        if (isset(self::$sessions[$player->getName()])) unset(self::$sessions[$player->getName()]);
    }
}