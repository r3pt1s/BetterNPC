<?php

namespace r3pt1s\betternpc;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use r3pt1s\betternpc\command\EntityMainCommand;
use r3pt1s\betternpc\entity\BetterEntityTypes;
use r3pt1s\betternpc\entity\util\EntityGlobalSettings;
use r3pt1s\betternpc\entity\util\EmoteList;
use r3pt1s\betternpc\listener\EventListener;

class Main extends PluginBase {
    use SingletonTrait;

    public const PREFIX = "§8[§6BetterNPC§8] §7";

    protected function onLoad(): void {
        self::setInstance($this);
        $this->saveDefaultConfig();

        EmoteList::loadEmotes($this->getConfig());
        EntityGlobalSettings::loadSettings($this->getConfig());
    }

    protected function onEnable(): void {
        BetterEntityTypes::init();

        if (!file_exists($this->getDataFolder() . "skins/")) mkdir($this->getDataFolder() . "skins/");

        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        $this->getServer()->getCommandMap()->register("betterNPC", new EntityMainCommand());
    }

    public function getSkinsPath(): string {
        return $this->getDataFolder() . "skins/";
    }
}