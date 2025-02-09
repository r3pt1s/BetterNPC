<?php

namespace r3pt1s\betternpc\form;

use dktapps\pmforms\CustomForm;
use dktapps\pmforms\CustomFormResponse;
use dktapps\pmforms\element\Dropdown;
use dktapps\pmforms\element\Input;
use dktapps\pmforms\element\Label;
use dktapps\pmforms\element\Slider;
use dktapps\pmforms\element\Toggle;
use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use pocketmine\player\Player;
use r3pt1s\betternpc\entity\action\EntityActionIds;
use r3pt1s\betternpc\entity\BetterEntity;
use r3pt1s\betternpc\entity\BetterEntityTypes;
use r3pt1s\betternpc\entity\data\BetterEntityData;
use r3pt1s\betternpc\entity\data\BetterEntitySettings;
use r3pt1s\betternpc\entity\impl\BetterHuman;
use r3pt1s\betternpc\entity\impl\BetterVillager;
use r3pt1s\betternpc\entity\impl\BetterZombie;
use r3pt1s\betternpc\entity\util\EmoteList;
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
                new Dropdown("hitAction", "What should happen if you hit the entity?", ["RUN_COMMAND", "DO_EMOTE", "SEND_MESSAGE", "NOTHING"], 3),
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

                if ($hitAction == 3) {
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

    public static function createEntityHitActionForm(string $type, string $nameTag, string $scoreTag, float $scale, bool $nameTagAlwaysVisible, bool $lookToPlayers, bool $doRandomEmotes, int $id, string $cmd = "/command", ?string $message = null): CustomForm {
        $emotes = EmoteList::getEmoteNames();
        return new CustomForm(
            "Create an entity",
            array_merge(match ($id) {
                EntityActionIds::ACTION_RUN_COMMAND => [new Input("actionData", "Please provide a command to run!", $cmd, $cmd)],
                EntityActionIds::ACTION_DO_EMOTE => [new Dropdown("actionData", "Please provide an emote!", $emotes)],
                EntityActionIds::ACTION_SEND_MESSAGE => [new Input("actionData", "Please provide a message!")],
                default => [new Label("text", "§cSomething went wrong, entity action was not recognized...")]
            }, ($message !== null ? [new Label("content", $message)] : [])),
            function (Player $player, CustomFormResponse $response) use($emotes, $type, $nameTag, $scoreTag, $scale, $nameTagAlwaysVisible, $lookToPlayers, $doRandomEmotes, $id): void {
                if (!EntityActionIds::check($id)) return;
                $actionDataRaw = $id == EntityActionIds::ACTION_DO_EMOTE ? (EmoteList::getEmoteIdByName($emotes[$response->getInt("actionData")]) ?? null) : trim($response->getString("actionData"));
                $actionData = EntityActionIds::fromIdData($id, $actionDataRaw);

                if ($id == EntityActionIds::ACTION_RUN_COMMAND) {
                    if (!str_starts_with($actionDataRaw, "/")) {
                        $player->sendForm(self::createEntityHitActionForm(
                            $type,
                            $nameTag,
                            $scoreTag,
                            $scale,
                            $nameTagAlwaysVisible,
                            $lookToPlayers,
                            $doRandomEmotes,
                            $id,
                            $actionDataRaw,
                            "Please put a '/' before the command."
                        ));
                        return;
                    }
                }

                $data = BetterEntityData::create(
                    $type,
                    $nameTag,
                    $scoreTag,
                    $scale,
                    BetterEntitySettings::create($nameTagAlwaysVisible, $lookToPlayers, $doRandomEmotes),
                    $actionData,
                    $type == BetterEntityTypes::TYPE_HUMAN ? SkinModel::fromSkin($player->getName(), $player->getSkin()) : null
                );

                $entity = $data->buildEntity($player->getLocation());
                if ($id == EntityActionIds::ACTION_RUN_COMMAND) {
                    $entity->addCommand($actionDataRaw);
                }

                $entity->spawnToAll();
                $player->sendMessage(Main::PREFIX . "Successfully §acreated §7the entity!");
            }
        );
    }

    public static function editEntityForm(BetterEntity $entity): MenuForm {
        $options = [new MenuOption("Edit data")];

        if ($entity->getEntityData()->getSettings()->isDoRandomEmotes()) {
            $options[] = new MenuOption("Add emote");
            $options[] = new MenuOption("Remove emote");
            $options[] = new MenuOption("List emotes");
        }

        if ($entity->getEntityData()->getHitAction()?->getId() === EntityActionIds::ACTION_RUN_COMMAND) {
            $options[] = new MenuOption("Add command");
            $options[] = new MenuOption("Remove command");
            $options[] = new MenuOption("List commands");
        }

        $options[] = new MenuOption("Apply inv layout");
        $options[] = new MenuOption("Teleport");

        return new MenuForm(
            "Edit an entity",
            "To put armor on the entity, you need to have the armor you want him to wear equipped, then click on 'Apply inv layout'.",
            $options,
            function (Player $player, int $data) use($entity, $options): void {
                $text = $options[$data]->getText();
                if ($data == 0) {
                    $player->sendForm(self::editEntityDataForm($entity));
                    return;
                } else if ($data == (count($options) - 2)) {
                    $normal = false;
                    $armor = false;
                    $offHand = false;

                    if ($entity instanceof BetterVillager || $entity instanceof BetterZombie) {
                        $armor = true;
                        $entity->getArmorInventory()->setContents($player->getArmorInventory()->getContents(true));
                    } else if ($entity instanceof BetterHuman) {
                        $normal = true;
                        $armor = true;
                        $offHand = true;
                        $entity->getInventory()->setItemInHand($player->getInventory()->getItemInHand());
                        $entity->getArmorInventory()->setContents($player->getArmorInventory()->getContents(true));
                        $entity->getOffHandInventory()->setContents($player->getOffHandInventory()->getContents(true));
                    }

                    if ($normal) $player->sendMessage(Main::PREFIX . "Successfully §aapplied §7your current in-hand item!");
                    else $player->sendMessage(Main::PREFIX . "§cCouldn't §7apply your current in-hand item!");
                    if ($armor) $player->sendMessage(Main::PREFIX . "Successfully §aapplied §7your current armor!");
                    else $player->sendMessage(Main::PREFIX . "§cCouldn't §7apply your current armor!");
                    if ($offHand) $player->sendMessage(Main::PREFIX . "Successfully §aapplied §7your current off-hand item!");
                    else $player->sendMessage(Main::PREFIX . "§cCouldn't §7apply your current off-hand item!");

                    return;
                } else if ($data == (count($options) - 1)) {
                    $player->teleport($entity->getEntity()->getPosition());
                    return;
                }

                if ($text == "Add emote") {
                    if (count($entity->getEmotes()) < count(EmoteList::getEmotes())) $player->sendForm(self::editEntityAddEmoteForm($entity));
                    else $player->sendMessage(Main::PREFIX . "§cThe entity already has every emote assigned!");
                } else if ($text == "Remove emote") {
                    if (count($entity->getEmotes()) > 0) $player->sendForm(self::editEntityRemoveEmoteForm($entity));
                    else $player->sendMessage(Main::PREFIX . "§cThe entity doesn't have any emotes!");
                } else if ($text == "List emotes") {
                    if (count($entity->getEmotes()) > 0) $player->sendForm(self::editEntityListEmotesForm($entity));
                    else $player->sendMessage(Main::PREFIX . "§cThe entity doesn't have any emotes!");
                } else if ($text == "Add command") {
                    $player->sendForm(self::editEntityAddCommandForm($entity));
                } else if ($text == "Remove command") {
                    if (count($entity->getCommands()) > 0) $player->sendForm(self::editEntityRemoveCommandForm($entity));
                    else $player->sendMessage(Main::PREFIX . "§cThe entity doesn't have any commands!");
                } else if ($text == "List commands") {
                    if (count($entity->getCommands()) > 0) $player->sendForm(self::editEntityListCommandsForm($entity));
                    else $player->sendMessage(Main::PREFIX . "§cThe entity doesn't have any commands!");
                }
            }
        );
    }

    public static function editEntityDataForm(BetterEntity $entity): CustomForm {
        $hitActionIndex = $entity->getEntityData()->getHitAction()?->getId() ?? 3;
        return new CustomForm(
            "Edit an entity",
            [
                new Input("nameTag", "Current nameTag", $entity->getNameTag(), $entity->getNameTag()),
                new Input("scoreTag", "Current scoreTag", $entity->getScoreTag(), $entity->getScoreTag()),
                new Slider("scale", "Current size of the entity", 0.5, 2, 0.1, $entity->getScale()),
                new Dropdown("hitAction", "Current hitAction", ["RUN_COMMAND", "DO_EMOTE", "SEND_MESSAGE", "NOTHING"], $hitActionIndex),
                new Toggle("nameTagAlwaysVisible", "Is nameTag always visible?", $entity->getEntityData()->getSettings()->isNameTagAlwaysVisible()),
                new Toggle("lookToPlayers", "Is looking to players?", $entity->getEntityData()->getSettings()->isLookToPlayers()),
                new Toggle("doRandomEmotes", "Is doing random emotes?", $entity->getEntityData()->getSettings()->isDoRandomEmotes())
            ],
            function (Player $player, CustomFormResponse $response) use($entity, $hitActionIndex): void {
                $nameTag = trim($response->getString("nameTag"));
                $scoreTag = trim($response->getString("scoreTag"));
                $scale = $response->getFloat("scale");
                $hitAction = $response->getInt("hitAction");
                $nameTagAlwaysVisible = $response->getBool("nameTagAlwaysVisible");
                $lookToPlayers = $response->getBool("lookToPlayers");
                $doRandomEmotes = $response->getBool("doRandomEmotes");

                if ($hitAction == 3 || $hitAction == $hitActionIndex) {
                    $entity->getEntity()->setNameTag($nameTag);
                    $entity->getEntity()->setScoreTag($scoreTag);
                    $entity->getEntity()->setScale($scale);
                    $entity->getEntity()->setNameTagAlwaysVisible($nameTagAlwaysVisible);
                    $entity->getEntityData()->setHitAction($hitAction == 3 ? null : $entity->getEntityData()->getHitAction());
                    $entity->getEntityData()->getSettings()->setLookToPlayers($lookToPlayers);
                    $entity->getEntityData()->getSettings()->setDoRandomEmotes($doRandomEmotes);
                    $player->sendMessage(Main::PREFIX . "Successfully §eedited §7the entity!");
                } else $player->sendForm(self::editEntityHitActionForm($entity, $hitAction, $nameTag, $scoreTag, $scale, $nameTagAlwaysVisible, $lookToPlayers, $doRandomEmotes));
            }
        );
    }

    public static function editEntityHitActionForm(BetterEntity $entity, int $id, string $nameTag, string $scoreTag, float $scale, bool $nameTagAlwaysVisible, bool $lookToPlayers, bool $doRandomEmotes, string $cmd = "/command", ?string $message = null): CustomForm {
        $emotes = EmoteList::getEmoteNames();
        return new CustomForm(
            "Edit an entity",
            array_merge(match ($id) {
                EntityActionIds::ACTION_RUN_COMMAND => [new Input("actionData", "Please provide a command to run!", $cmd, $cmd)],
                EntityActionIds::ACTION_DO_EMOTE => [new Dropdown("actionData", "Please provide an emote!", $emotes)],
                EntityActionIds::ACTION_SEND_MESSAGE => [new Input("actionData", "Please provide a message!")],
                default => [new Label("text", "§cSomething went wrong, entity action was not recognized...")]
            }, ($message !== null ? [new Label("content", $message)] : [])),
            function (Player $player, CustomFormResponse $response) use($emotes, $nameTag, $scoreTag, $scale, $nameTagAlwaysVisible, $lookToPlayers, $doRandomEmotes, $entity, $id): void {
                if (!EntityActionIds::check($id)) return;
                $actionDataRaw = $id == EntityActionIds::ACTION_DO_EMOTE ? (EmoteList::getEmoteIdByName($emotes[$response->getInt("actionData")]) ?? null) : trim($response->getString("actionData"));
                $actionData = EntityActionIds::fromIdData($id, $actionDataRaw);

                if ($id == EntityActionIds::ACTION_RUN_COMMAND) {
                    if (!str_starts_with($actionDataRaw, "/")) {
                        $player->sendForm(self::editEntityHitActionForm(
                            $entity,
                            $id,
                            $nameTag,
                            $scoreTag,
                            $scale,
                            $nameTagAlwaysVisible,
                            $lookToPlayers,
                            $doRandomEmotes,
                            $actionDataRaw,
                            "Please put a '/' before the command."
                        ));
                        return;
                    }

                    $entity->addCommand($actionDataRaw);
                }

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

    public static function editEntityAddEmoteForm(BetterEntity $entity): MenuForm {
        $emotes = array_values(array_filter(EmoteList::getEmoteNames(), fn(string $name) => !$entity->checkEmote(EmoteList::getEmoteIdByName($name))));
        return new MenuForm(
            "Add Emote",
            "Choose an emote",
            array_map(fn(string $name) => new MenuOption($name), $emotes),
            function (Player $player, int $data) use($emotes, $entity): void {
                $emote = ($name = $emotes[$data] ?? null) !== null ? (EmoteList::getEmoteIdByName($name) ?? null) : null;
                if ($emote !== null) {
                    $entity->addEmote($emote);
                    $player->sendMessage(Main::PREFIX . "Successfully §aadded §7the emote to the entity!");
                } else $player->sendMessage(Main::PREFIX . "§cFailed to add the emote to the npc! §8(§cEmote not found§8)");
            }
        );
    }

    public static function editEntityRemoveEmoteForm(BetterEntity $entity): MenuForm {
        $emotes = array_values(array_filter(EmoteList::getEmotes(), fn(array $emote) => $entity->checkEmote($emote["id"])));
        return new MenuForm(
            "Remove Emote",
            "Choose an emote",
            array_map(fn(array $emote) => new MenuOption($emote["name"]), $emotes),
            function (Player $player, int $data) use($emotes, $entity): void {
                $emote = ($e = $emotes[$data] ?? null) !== null ? $e["id"] : null;
                if ($emote !== null) {
                    $entity->removeEmote($emote);
                    $player->sendMessage(Main::PREFIX . "Successfully §cremoved §7the emote from the entity!");
                } else $player->sendMessage(Main::PREFIX . "§cFailed to remove the emote from the entity! §8(§cEmote not found§8)");
            }
        );
    }

    public static function editEntityListEmotesForm(BetterEntity $entity): MenuForm {
        $emotes = $entity->getEmotes();
        return new MenuForm(
            "List Emotes",
            "",
            array_map(fn(string $id) => new MenuOption(EmoteList::getNameById($id)), $emotes),
            function (Player $player, int $data) use($emotes, $entity): void {}
        );
    }

    public static function editEntityAddCommandForm(BetterEntity $entity, ?string $message = null, string $command = ""): CustomForm {
        return new CustomForm(
            "Add Command",
            array_merge([
                new Input("command", "Input the command (with the /)", $command, $command)
            ], ($message === null ? [] : [
                new Label("content", $message)
            ])),
            function (Player $player, CustomFormResponse $response) use($entity): void {
                if (!str_starts_with($cmd = trim($response->getString("command")), "/")) {
                    $player->sendForm(self::editEntityAddCommandForm($entity, "Please put a '/' before the command.", $cmd));
                    return;
                }

                $entity->addCommand($cmd);
                $player->sendMessage(Main::PREFIX . "Successfully §aadded §7the command to the entity!");
            }
        );
    }

    public static function editEntityRemoveCommandForm(BetterEntity $entity): MenuForm {
        $options = [];
        $i = 0;
        foreach (($commands = $entity->getCommands()) as $command) {
            $options[] = new MenuOption(++$i . ".: " . $command);
        }

        return new MenuForm(
            "Remove Command",
            "Choose a command",
            $options,
            function (Player $player, int $data) use($commands, $entity): void {
                $command = $commands[$data] ?? null;
                if ($command !== null) {
                    $entity->removeCommand($command);
                    $player->sendMessage(Main::PREFIX . "Successfully §cremoved §7the command from the entity!");
                } else $player->sendMessage(Main::PREFIX . "§cFailed to remove the command from the entity! §8(§cCommand not found§8)");
            }
        );
    }

    public static function editEntityListCommandsForm(BetterEntity $entity): MenuForm {
        $options = [];
        $i = 0;
        foreach ($entity->getCommands() as $command) {
            $options[] = new MenuOption(++$i . ".: " . $command);
        }

        return new MenuForm(
            "List Commands",
            "",
            $options,
            function (Player $player, int $data) use($entity): void {}
        );
    }
}