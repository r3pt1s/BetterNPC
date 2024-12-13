<?php

namespace r3pt1s\betternpc;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use r3pt1s\betternpc\command\EntityMainCommand;
use r3pt1s\betternpc\entity\BetterEntityTypes;
use r3pt1s\betternpc\listener\EventListener;

class Main extends PluginBase {
    use SingletonTrait;

    protected function onLoad(): void {
        self::setInstance($this);
    }

    protected function onEnable(): void {
        BetterEntityTypes::init();

        if (!file_exists($this->getDataFolder() . "skins/")) mkdir($this->getDataFolder() . "skins/");

        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        $this->getServer()->getCommandMap()->register("betterNPc", new EntityMainCommand());
    }

    public function getSkinsPath(): string {
        return $this->getDataFolder() . "skins/";
    }
}