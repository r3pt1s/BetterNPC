<?php

namespace r3pt1s\betternpc\form;

use dktapps\pmforms\CustomForm;
use dktapps\pmforms\CustomFormResponse;
use dktapps\pmforms\element\Dropdown;
use dktapps\pmforms\element\Input;
use dktapps\pmforms\element\Label;
use dktapps\pmforms\element\Slider;
use pocketmine\player\Player;
use r3pt1s\betternpc\entity\action\EntityActionIds;
use r3pt1s\betternpc\entity\BetterEntityTypes;
use r3pt1s\betternpc\entity\data\BetterEntityData;
use r3pt1s\betternpc\entity\model\SkinModel;
use r3pt1s\betternpc\Main;

final class FormManager {

    public static function createEntityForm(): CustomForm {
        return new CustomForm(
            "Create a entity",
            [
                new Dropdown("type", "Please select an entity type", BetterEntityTypes::getAll()),
                new Input("nameTag", "Please set the nameTag", "You are cool!", ""),
                new Input("scoreTag", "You can provide a scoreTag"),
                new Slider("scale", "Please select the size of the entity", 0.5, 2, 0.1, 1),
                new Dropdown("hitAction", "What should happen if you hit the entity?", ["RUN_COMMAND", "DO_EMOTE", "DO_ANIMATION", "SEND_MESSAGE", "NOTHING"], 4)
            ],
            function (Player $player, CustomFormResponse $response): void {
                $type = BetterEntityTypes::getAll()[$response->getInt("type")];
                $nameTag = trim($response->getString("nameTag"));
                $scoreTag = trim($response->getString("scoreTag"));
                $scale = $response->getFloat("scale");
                $hitAction = $response->getInt("hitAction");

                if ($hitAction == 4) {
                    $data = BetterEntityData::create(
                        $type,
                        $nameTag,
                        $scoreTag,
                        $scale,
                        null,
                        $type == BetterEntityTypes::TYPE_HUMAN ? SkinModel::fromSkin($player->getName(), $player->getSkin()) : null
                    );

                    $data->buildEntity($player->getLocation())->spawnToAll();
                } else $player->sendForm(self::createEntityHitActionForm($type, $nameTag, $scoreTag, $scale, $hitAction));
            }
        );
    }

    public static function createEntityHitActionForm(string $type, string $nameTag, string $scoreTag, float $scale, int $id): CustomForm {
        return new CustomForm(
            "Create a entity",
            match ($id) {
                EntityActionIds::ACTION_RUN_COMMAND => [new Input("actionData", "Please provide a command to run!", "/ban")],
                EntityActionIds::ACTION_DO_EMOTE => [new Input("actionData", "Please provide an emoteId!")],
                EntityActionIds::ACTION_DO_ANIMATION => [new Input("actionData", "Please provide an animation!")],
                EntityActionIds::ACTION_SEND_MESSAGE => [new Input("actionData", "Please provide a message!")],
                default => [new Label("text", "§cSomething went wrong, entity action was not recognized...")]
            },
            function (Player $player, CustomFormResponse $response) use($type, $nameTag, $scoreTag, $scale, $id): void {
                $actionData = EntityActionIds::fromIdData($id, $response->getString("actionData"));
                $data = BetterEntityData::create(
                    $type,
                    $nameTag,
                    $scoreTag,
                    $scale,
                    $actionData,
                    $type == BetterEntityTypes::TYPE_HUMAN ? SkinModel::fromSkin($player->getName(), $player->getSkin()) : null
                );

                $data->buildEntity($player->getLocation())->spawnToAll();
                $player->sendMessage(Main::PREFIX . "Successfully §acreated §7the entity!");
            }
        );
    }
}