<?php

namespace r3pt1s\betternpc\entity\action;

use r3pt1s\betternpc\entity\action\impl\EntityDoAnimationAction;
use r3pt1s\betternpc\entity\action\impl\EntityDoEmoteAction;
use r3pt1s\betternpc\entity\action\impl\EntityOpenDialogueAction;
use r3pt1s\betternpc\entity\action\impl\EntityRunCommandAction;
use r3pt1s\betternpc\entity\action\impl\EntitySendMessageAction;

final class EntityActionIds {

    public const ACTION_RUN_COMMAND = 0;
    public const ACTION_DO_EMOTE = 1;
    public const ACTION_DO_ANIMATION = 2;
    public const ACTION_OPEN_DIALOGUE = 3;
    public const ACTION_SEND_MESSAGE = 4;

    public static function fromId(int $id, array $data): ?IEntityAction {
        return match ($id) {
            self::ACTION_RUN_COMMAND => EntityRunCommandAction::fromData($data),
            self::ACTION_DO_EMOTE => EntityDoEmoteAction::fromData($data),
            self::ACTION_DO_ANIMATION => EntityDoAnimationAction::fromData($data),
            self::ACTION_OPEN_DIALOGUE => EntityOpenDialogueAction::fromData($data),
            self::ACTION_SEND_MESSAGE => EntitySendMessageAction::fromData($data),
            default => null
        };
    }
}