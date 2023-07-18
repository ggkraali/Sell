<?php

namespace ayd1ndemirci\SellSystem\form;

use dktapps\pmforms\ModalForm;
use pocketmine\player\Player;

class InformationForm extends ModalForm
{
    /**
     * @param string $title
     * @param string $content
     * @param string $yesButton
     * @param string $noButton
     */
    public function __construct(string $title, string $content, string $yesButton, string $noButton)
    {
        parent::__construct(
            $title,
            $content,
            function (Player $player, bool $data): void{},
            $yesButton,
            $noButton
        );
    }
}