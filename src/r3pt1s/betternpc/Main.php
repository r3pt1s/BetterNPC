<?php

namespace r3pt1s\betternpc;

use cosmicpe\npcdialogue\NpcDialogueManager;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use r3pt1s\betternpc\entity\BetterEntityTypes;
use r3pt1s\betternpc\listener\EventListener;

class Main extends PluginBase {
    use SingletonTrait;

    protected function onLoad(): void {
        self::setInstance($this);
    }

    protected function onEnable(): void {
        BetterEntityTypes::init();

        NpcDialogueManager::register($this);
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
    }
}