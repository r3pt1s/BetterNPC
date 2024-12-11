<?php

namespace r3pt1s\betternpc\entity\dialogue;

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
}