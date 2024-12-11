<?php

namespace r3pt1s\betternpc\entity\dialogue\action;

use r3pt1s\betternpc\entity\dialogue\action\impl\EntityDialogueButtonDoAnimationAction;
use r3pt1s\betternpc\entity\dialogue\action\impl\EntityDialogueButtonDoEmoteAction;
use r3pt1s\betternpc\entity\dialogue\action\impl\EntityDialogueButtonOpenDialogueAction;
use r3pt1s\betternpc\entity\dialogue\action\impl\EntityDialogueButtonRunCommandAction;
use r3pt1s\betternpc\entity\dialogue\action\impl\EntityDialogueButtonSendMessageAction;

final class EntityDialogueButtonActions {

    public const ACTION_RUN_COMMAND = 0;
    public const ACTION_DO_EMOTE = 1;
    public const ACTION_DO_ANIMATION = 2;
    public const ACTION_OPEN_DIALOGUE = 3;
    public const ACTION_SEND_MESSAGE = 4;

    //use 'fromData(array $data)'
    public static function classFromId(int $id): ?string {
        return match ($id) {
            self::ACTION_RUN_COMMAND => EntityDialogueButtonRunCommandAction::class,
            self::ACTION_DO_EMOTE => EntityDialogueButtonDoEmoteAction::class,
            self::ACTION_DO_ANIMATION => EntityDialogueButtonDoAnimationAction::class,
            self::ACTION_OPEN_DIALOGUE => EntityDialogueButtonOpenDialogueAction::class,
            self::ACTION_SEND_MESSAGE => EntityDialogueButtonSendMessageAction::class,
            default => null
        };
    }
}