<?php

namespace r3pt1s\betternpc\form;

use dktapps\pmforms\CustomForm;
use dktapps\pmforms\CustomFormResponse;
use dktapps\pmforms\element\Dropdown;
use dktapps\pmforms\element\Input;
use dktapps\pmforms\element\Label;
use dktapps\pmforms\element\Slider;
use dktapps\pmforms\element\Toggle;
use pocketmine\player\Player;
use r3pt1s\betternpc\entity\action\EntityActionIds;
use r3pt1s\betternpc\entity\BetterEntity;
use r3pt1s\betternpc\entity\BetterEntityTypes;
use r3pt1s\betternpc\entity\data\BetterEntityData;
use r3pt1s\betternpc\entity\data\BetterEntitySettings;
use r3pt1s\betternpc\entity\model\SkinModel;
use r3pt1s\betternpc\Main;

final class FormManager {

    public static function createEntityForm(): CustomForm {
        return new CustomForm(
            "Create an entity",
            [
                new Dropdown("type", "Please select an entity type", BetterEntityTypes::getAll()),
                new Input("nameTag", "Please set the nameTag", "You are cool!", ""),
                new Input("scoreTag", "You can provide a scoreTag"),
                new Slider("scale", "Please select the size of the entity", 0.5, 2, 0.1, 1),
                new Dropdown("hitAction", "What should happen if you hit the entity?", ["RUN_COMMAND", "DO_EMOTE", "DO_ANIMATION", "SEND_MESSAGE", "NOTHING"], 4),
                new Toggle("nameTagAlwaysVisible", "Should the nameTag always be visible?", true),
                new Toggle("lookToPlayers", "Should the entity look to players?"),
                new Toggle("doRandomEmotes", "Should the entity do random emotes?")
            ],
            function (Player $player, CustomFormResponse $response): void {
                $type = BetterEntityTypes::getAll()[$response->getInt("type")];
                $nameTag = trim($response->getString("nameTag"));
                $scoreTag = trim($response->getString("scoreTag"));
                $scale = $response->getFloat("scale");
                $hitAction = $response->getInt("hitAction");
                $nameTagAlwaysVisible = $response->getBool("nameTagAlwaysVisible");
                $lookToPlayers = $response->getBool("lookToPlayers");
                $doRandomEmotes = $response->getBool("doRandomEmotes");

                if ($hitAction == 4) {
                    $data = BetterEntityData::create(
                        $type,
                        $nameTag,
                        $scoreTag,
                        $scale,
                        BetterEntitySettings::create($nameTagAlwaysVisible, $lookToPlayers, $doRandomEmotes),
                        null,
                        $type == BetterEntityTypes::TYPE_HUMAN ? SkinModel::fromSkin($player->getName(), $player->getSkin()) : null
                    );

                    $data->buildEntity($player->getLocation())->spawnToAll();
                    $player->sendMessage(Main::PREFIX . "Successfully §acreated §7the entity!");
                } else $player->sendForm(self::createEntityHitActionForm($type, $nameTag, $scoreTag, $scale, $nameTagAlwaysVisible, $lookToPlayers, $doRandomEmotes, $hitAction));
            }
        );
    }

    public static function createEntityHitActionForm(string $type, string $nameTag, string $scoreTag, float $scale, bool $nameTagAlwaysVisible, bool $lookToPlayers, bool $doRandomEmotes, int $id): CustomForm {
        return new CustomForm(
            "Create an entity",
            match ($id) {
                EntityActionIds::ACTION_RUN_COMMAND => [new Input("actionData", "Please provide a command to run!", "/ban")],
                EntityActionIds::ACTION_DO_EMOTE => [new Input("actionData", "Please provide an emoteId!")],
                EntityActionIds::ACTION_DO_ANIMATION => [new Input("actionData", "Please provide an animation!")],
                EntityActionIds::ACTION_SEND_MESSAGE => [new Input("actionData", "Please provide a message!")],
                default => [new Label("text", "§cSomething went wrong, entity action was not recognized...")]
            },
            function (Player $player, CustomFormResponse $response) use($type, $nameTag, $scoreTag, $scale, $nameTagAlwaysVisible, $lookToPlayers, $doRandomEmotes, $id): void {
                if (!EntityActionIds::check($id)) return;
                $actionData = EntityActionIds::fromIdData($id, $response->getString("actionData"));
                $data = BetterEntityData::create(
                    $type,
                    $nameTag,
                    $scoreTag,
                    $scale,
                    BetterEntitySettings::create($nameTagAlwaysVisible, $lookToPlayers, $doRandomEmotes),
                    $actionData,
                    $type == BetterEntityTypes::TYPE_HUMAN ? SkinModel::fromSkin($player->getName(), $player->getSkin()) : null
                );

                $data->buildEntity($player->getLocation())->spawnToAll();
                $player->sendMessage(Main::PREFIX . "Successfully §acreated §7the entity!");
            }
        );
    }

    public static function editEntityForm(BetterEntity $entity): CustomForm {
        $hitActionIndex = $entity->getEntityData()->getHitAction()?->getId() ?? 4;
        return new CustomForm(
            "Edit an entity",
            [
                new Input("nameTag", "Current nameTag", $entity->getNameTag(), $entity->getNameTag()),
                new Input("scoreTag", "Current scoreTag", $entity->getScoreTag(), $entity->getScoreTag()),
                new Slider("scale", "Current size of the entity", 0.5, 2, 0.1, $entity->getScale()),
                new Dropdown("hitAction", "Current hitAction", ["RUN_COMMAND", "DO_EMOTE", "DO_ANIMATION", "SEND_MESSAGE", "NOTHING"], $hitActionIndex),
                new Toggle("nameTagAlwaysVisible", "Is nameTag always visible?", $entity->getEntityData()->getSettings()->isNameTagAlwaysVisible()),
                new Toggle("lookToPlayers", "Is looking to players?", $entity->getEntityData()->getSettings()->isLookToPlayers()),
                new Toggle("doRandomEmotes", "Is doing random emotes?", $entity->getEntityData()->getSettings()->isDoRandomEmotes())
            ],
            function (Player $player, CustomFormResponse $response) use($entity): void {
                $nameTag = trim($response->getString("nameTag"));
                $scoreTag = trim($response->getString("scoreTag"));
                $scale = $response->getFloat("scale");
                $hitAction = $response->getInt("hitAction");
                $nameTagAlwaysVisible = $response->getBool("nameTagAlwaysVisible");
                $lookToPlayers = $response->getBool("lookToPlayers");
                $doRandomEmotes = $response->getBool("doRandomEmotes");

                if ($hitAction == 4) {
                    $entity->getEntity()->setNameTag($nameTag);
                    $entity->getEntity()->setScoreTag($scoreTag);
                    $entity->getEntity()->setScale($scale);
                    $entity->getEntity()->setNameTagAlwaysVisible($nameTagAlwaysVisible);
                    $entity->getEntityData()->setHitAction(null);
                    $entity->getEntityData()->getSettings()->setLookToPlayers($lookToPlayers);
                    $entity->getEntityData()->getSettings()->setDoRandomEmotes($doRandomEmotes);
                    $player->sendMessage(Main::PREFIX . "Successfully §eedited §7the entity!");
                } else $player->sendForm(self::editEntityHitActionForm($entity, $hitAction, $nameTag, $scoreTag, $scale, $nameTagAlwaysVisible, $lookToPlayers, $doRandomEmotes));
            }
        );
    }

    public static function editEntityHitActionForm(BetterEntity $entity, int $id, string $nameTag, string $scoreTag, float $scale, bool $nameTagAlwaysVisible, bool $lookToPlayers, bool $doRandomEmotes): CustomForm {
        return new CustomForm(
            "Edit an entity",
            match ($id) {
                EntityActionIds::ACTION_RUN_COMMAND => [new Input("actionData", "Please provide a command to run!", "/ban")],
                EntityActionIds::ACTION_DO_EMOTE => [new Input("actionData", "Please provide an emoteId!")],
                EntityActionIds::ACTION_DO_ANIMATION => [new Input("actionData", "Please provide an animation!")],
                EntityActionIds::ACTION_SEND_MESSAGE => [new Input("actionData", "Please provide a message!")],
                default => [new Label("text", "§cSomething went wrong, entity action was not recognized...")]
            },
            function (Player $player, CustomFormResponse $response) use($nameTag, $scoreTag, $scale, $nameTagAlwaysVisible, $lookToPlayers, $doRandomEmotes, $entity, $id): void {
                if (!EntityActionIds::check($id)) return;
                $actionData = EntityActionIds::fromIdData($id, $response->getString("actionData"));
                $entity->getEntity()->setNameTag($nameTag);
                $entity->getEntity()->setScoreTag($scoreTag);
                $entity->getEntity()->setScale($scale);
                $entity->getEntity()->setNameTagAlwaysVisible($nameTagAlwaysVisible);
                $entity->getEntityData()->setHitAction($actionData);
                $entity->getEntityData()->getSettings()->setLookToPlayers($lookToPlayers);
                $entity->getEntityData()->getSettings()->setDoRandomEmotes($doRandomEmotes);
                $player->sendMessage(Main::PREFIX . "Successfully §eedited §7the entity!");
            }
        );
    }
}