<?php

namespace ayd1ndemirci\SellSystem\form;

use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use pocketmine\player\Player;

class SellForm extends MenuForm
{

    public function __construct()
    {
        parent::__construct(
            "Sat Menüsü",
            "",
            [
                new MenuOption("Tümünü Sat"),
                new MenuOption("Seçerek Sat"),
                new MenuOption("Elindeki Eşyayı Sat"),
                new MenuOption("Fiyatlar Listesi")
            ],
            function (Player $player, int $data): void
            {
                match ($data) {
                    0 => $player->sendForm(new SellAllForm()),
                    1 => $player->sendForm(new SellSelectForm($player)),
                    2 => $player->sendForm(new SellHandForm($player)),
                    3 => $player->sendForm(new SellListForm($player))
                };
            }
        );
    }
}