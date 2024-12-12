<?php

namespace r3pt1s\betternpc\entity\dialogue;

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;

final class EntityDialogue {

    /**
     * @param string $id
     * @param string $baseText
     * @param array<EntityDialogueButton> $buttons
     */
    public function __construct(
        private readonly string $id,
        private string $baseText,
        private array $buttons
    ) {}

    public function getId(): string {
        return $this->id;
    }

    public function getBaseText(): string {
        return $this->baseText;
    }

    public function setBaseText(string $baseText): void {
        $this->baseText = $baseText;
    }

    public function getButtons(): array {
        return $this->buttons;
    }

    public function addButton(EntityDialogueButton $button): void {
        if (!isset($this->buttons[$button->getId()])) $this->buttons[$button->getId()] = $button;
    }

    public function removeButton(EntityDialogueButton $button): void {
        if (isset($this->buttons[$button->getId()])) unset($this->buttons[$button->getId()]);
    }

    public function getButton(string $id): ?EntityDialogueButton {
        return $this->buttons[$id] ?? null;
    }

    public function toNbt(): CompoundTag {
        $buttons = CompoundTag::create();
        foreach ($this->buttons as $id => $button) {
            $buttons->setTag($id, $button->toNbt());
        }

        return CompoundTag::create()
            ->setString("id", $this->id)
            ->setString("baseText", $this->baseText)
            ->setTag("buttons", $buttons);
    }

    public static function fromNbt(CompoundTag $nbt): ?EntityDialogue {
        if (
            $nbt->getTag("id") instanceof StringTag &&
            $nbt->getTag("baseText") instanceof StringTag &&
            $nbt->getTag("buttons") instanceof CompoundTag
        ) {
            $buttons = [];
            foreach ($nbt->getCompoundTag("buttons")->getValue() as $button) {
                if (!$button instanceof CompoundTag) continue;
                if (($button = EntityDialogueButton::fromNbt($button)) !== null) $buttons[$button->getId()] = $button;
            }

            return new self(
                $nbt->getString("id"),
                $nbt->getString("baseText"),
                $buttons
            );
        }
        return null;
    }
}