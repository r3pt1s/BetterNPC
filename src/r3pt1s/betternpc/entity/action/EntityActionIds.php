<?php

namespace r3pt1s\betternpc\entity\action;

use pocketmine\nbt\tag\CompoundTag;
use r3pt1s\betternpc\entity\action\impl\EntityDoAnimationAction;
use r3pt1s\betternpc\entity\action\impl\EntityDoEmoteAction;
use r3pt1s\betternpc\entity\action\impl\EntityRunCommandAction;
use r3pt1s\betternpc\entity\action\impl\EntitySendMessageAction;

final class EntityActionIds {

    public const ACTION_RUN_COMMAND = 0;
    public const ACTION_DO_EMOTE = 1;
    public const ACTION_DO_ANIMATION = 2;
    public const ACTION_SEND_MESSAGE = 3;

    public static function fromId(int $id, CompoundTag $nbt): ?IEntityAction {
        return match ($id) {
            self::ACTION_RUN_COMMAND => EntityRunCommandAction::fromNbt($nbt),
            self::ACTION_DO_EMOTE => EntityDoEmoteAction::fromNbt($nbt),
            self::ACTION_DO_ANIMATION => EntityDoAnimationAction::fromNbt($nbt),
            self::ACTION_SEND_MESSAGE => EntitySendMessageAction::fromNbt($nbt),
            default => null
        };
    }
}