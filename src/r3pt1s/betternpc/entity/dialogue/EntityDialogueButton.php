<?php

namespace r3pt1s\betternpc\entity\dialogue;

use pocketmine\player\Player;
use r3pt1s\betternpc\entity\action\IEntityAction;
use r3pt1s\betternpc\entity\BetterEntity;

final class EntityDialogueButton {

    public function __construct(
        private readonly string $id,
        private string $text,
        private ?IEntityAction $onClickAction
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

    public function getOnClickAction(): ?IEntityAction {
        return $this->onClickAction;
    }

    public function setOnClickAction(?IEntityAction $onClickAction): void {
        $this->onClickAction = $onClickAction;
    }
}