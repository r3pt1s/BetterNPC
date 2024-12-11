<?php

namespace r3pt1s\betternpc\entity\dialogue;

use pocketmine\player\Player;
use r3pt1s\betternpc\entity\BetterEntity;
use r3pt1s\betternpc\entity\dialogue\action\IEntityDialogueButtonAction;

final class EntityDialogueButton {

    public function __construct(
        private readonly string $id,
        private string $text,
        private ?IEntityDialogueButtonAction $onClickAction
    ) {}

    public function onClick(Player $player, BetterEntity $entity): void {
        $this->onClickAction?->doAction($player, $entity);
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

    public function getOnClickAction(): ?IEntityDialogueButtonAction {
        return $this->onClickAction;
    }

    public function setOnClickAction(?IEntityDialogueButtonAction $onClickAction): void {
        $this->onClickAction = $onClickAction;
    }
}