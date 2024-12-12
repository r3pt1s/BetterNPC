<?php

namespace r3pt1s\betternpc\entity\dialogue;

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\player\Player;
use r3pt1s\betternpc\entity\action\EntityActionIds;
use r3pt1s\betternpc\entity\action\IEntityAction;
use r3pt1s\betternpc\entity\BetterEntity;

final class EntityDialogueButton {

    public function __construct(
        private readonly string $id,
        private string $text,
        private ?IEntityAction $clickAction
    ) {}

    public function onClick(Player $player, BetterEntity $entity): void {
        $this->clickAction?->doAction($player, $entity);
    }

    public function getId(): string {
        return $this->id;
    }

    public function getText(): string {
        return $this->text;
    }

    public function setText(string $text): void {
        $this->text = $text;
    }

    public function getClickAction(): ?IEntityAction {
        return $this->clickAction;
    }

    public function setClickAction(?IEntityAction $clickAction): void {
        $this->clickAction = $clickAction;
    }

    public function toNbt(): CompoundTag {
        return CompoundTag::create()
            ->setString("id", $this->id)
            ->setString("text", $this->text)
            ->setInt("clickActionId", $this->clickAction?->getId() ?? -1)
            ->setTag("clickAction", $this->clickAction?->toNbt() ?? CompoundTag::create());
    }

    public static function fromNbt(CompoundTag $nbt): ?EntityDialogueButton {
        if (
            $nbt->getTag("id") instanceof StringTag &&
            $nbt->getTag("text") instanceof StringTag &&
            $nbt->getTag("clickActionId") instanceof IntTag &&
            $nbt->getTag("clickAction") instanceof CompoundTag
        ) {
            if (($action = EntityActionIds::fromId($nbt->getInt("clickActionId"), $nbt->getCompoundTag("clickActionId"))) === null) return null;
            return new self(
                $nbt->getString("id"),
                $nbt->getString("text"),
                $action
            );
        }
        return null;
    }
}