<?php

namespace r3pt1s\betternpc\entity\trait;

use pocketmine\entity\Entity;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\MoveActorAbsolutePacket;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\Position;
use r3pt1s\betternpc\entity\data\BetterEntityData;
use r3pt1s\betternpc\entity\impl\BetterHuman;
use r3pt1s\betternpc\entity\util\EntityGlobalSettings;

trait EntityDataTrait {

    protected BetterEntityData $entityData;
    private int $hitActionCoolDown = 0;

    public function loadData(?CompoundTag $nbt): void {
        $this->setNameTag($this->entityData->getNameTag());
        $this->setScoreTag($this->entityData->getScoreTag());
        $this->setScale($this->entityData->getScale());
        $this->setNameTagAlwaysVisible($this->entityData->getSettings()->isNameTagAlwaysVisible());
        $this->loadEmotes($nbt?->getListTag("emotes"));
        $this->loadCommands($nbt?->getListTag("commands"));
    }

    public function onUpdate(int $currentTick): bool {
        if ($this instanceof BetterHuman) $this->emoteTick();

        if ($this->entityData->getSettings()->isLookToPlayers()) {
            foreach (Server::getInstance()->getOnlinePlayers() as $player) {
                if ($player->getPosition()->distanceSquared($this->getPosition()) <= 12) {
                    $target = $this instanceof BetterHuman ? $player->getOffsetPosition($player->getPosition()) : $player->getPosition()->add(0, (1.621 / 2), 0);
                    $horizontal = sqrt(($target->x - $this->getLocation()->x) ** 2 + ($target->z - $this->getLocation()->z) ** 2);
                    $vertical = $target->y - ($this->getLocation()->y + $this->getEyeHeight());
                    $pitch = -atan2($vertical, $horizontal) / M_PI * 180;

                    $xDist = $target->x - $this->getLocation()->x;
                    $zDist = $target->z - $this->getLocation()->z;

                    $yaw = atan2($zDist, $xDist) / M_PI * 180 - 90;
                    if ($yaw < 0) $yaw += 360.0;

                    $player->getNetworkSession()->sendDataPacket(MoveActorAbsolutePacket::create($this->getId(), Position::fromObject($this->getOffsetPosition($this->getPosition()), $this->getWorld()), $pitch, $yaw, $yaw, 0));
                }
            }
        }

        return parent::onUpdate($currentTick);
    }

    public function setNameTag(string $name): void {
        parent::setNameTag($name);
        $this->entityData->setNameTag($name);
    }

    public function setScoreTag(string $score): void {
        parent::setScoreTag($score);
        $this->entityData->setScoreTag($score);
    }

    public function setScale(float $value): void {
        parent::setScale($value);
        $this->entityData->setScale($value);
    }

    public function setNameTagAlwaysVisible(bool $value = true): void {
        parent::setNameTagAlwaysVisible($value);
        $this->entityData->getSettings()->setNameTagAlwaysVisible($value);
    }

    public function saveNBT(): CompoundTag {
        $nbt = parent::saveNBT();
        $nbt->setTag("entityData", $this->entityData->toNbt());
        $nbt->setTag("emotes", $this->convertEmotesToNbt());
        $nbt->setTag("commands", $this->convertCommandsToNbt());
        return $nbt;
    }

    public function onHit(Player $player): void {
        if (Server::getInstance()->getTick() > $this->hitActionCoolDown) {
            $this->entityData->getHitAction()?->doAction($player, $this);
            $this->hitActionCoolDown = Server::getInstance()->getTick() + (20 * EntityGlobalSettings::getIntSetting(EntityGlobalSettings::KEY_HIT_ACTION_COOLDOWN));
        }
    }

    public function getEntityData(): BetterEntityData {
        return $this->entityData;
    }

    public function getEntity(): Entity {
        return $this;
    }
}