<?php

namespace ayd1ndemirci\SellSystem\form;

use ayd1ndemirci\SellSystem\Main;
use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use pocketmine\item\StringToItemParser;
use pocketmine\player\Player;

class SellListForm extends MenuForm
{

    public function __construct()
    {
        $text = "";
        foreach (Main::getInstance()->getConfig()->get("Items") as $items) {
            $exp = explode(":", $items);
            $item = StringToItemParser::getInstance()->parse($exp[0]);
            $text .= "§a".$item->getName().": §f".$exp[1]. " TL\n";
        }
        parent::__construct(
            "Fiyat Listesi",
            $text,
            [
                new MenuOption("§cGeri"),
            ],
            function (Player $player, int $data): void
            {
                if ($data === 0) $player->sendForm(new SellForm());
            }
        );
    }
}