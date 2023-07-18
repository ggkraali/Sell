<?php

namespace ayd1ndemirci\SellSystem\form;

use ayd1ndemirci\SellSystem\Main;
use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\LegacyStringToItemParser;
use pocketmine\item\StringToItemParser;
use pocketmine\player\Player;

class SellSelectForm extends MenuForm
{
    public mixed $name;

    public mixed $price;

    public mixed $count;
    /**
     * @param Player $player
     */
    public function __construct(\pocketmine\player\Player $player)
    {
        $options = [];
        foreach (Main::getInstance()->getConfig()->get("Items") as $items) {
            $exp = explode(":", $items);
            $item = LegacyStringToItemParser::getInstance()->parse($exp[0]);
            $id = $item->getTypeId();
            $price = $exp[1];
            foreach ($player->getInventory()->getContents() as $content) {
                if ($content->getTypeId() == $id) {
                    $count = $content->getCount();
                    $text = $count."x ".$item->getName()." | ".$price." TL";
                    $options[] = new MenuOption($text);

                    $this->name[$text] = $content->getName();
                    $this->price[$text] = $price;
                    $this->count[$text] = $content->getCount();
                }
            }
        }
        $options[] = new MenuOption("§cGeri");
        parent::__construct(
            "Seçerek Sat",
            "\n§7Satılıcak eşyayı seç.\n\n",
            $options,
            function (Player $player, int $data): void
            {
                $text = $this->getOption($data)->getText();

                if ($text === "§cGeri") {
                    $player->sendForm(new SellForm());
                    return;
                }

                $name = $this->name[$text];
                $price = $this->price[$text];
                $count = $this->count[$text];

                $player->sendForm(new SellSelectedItemForm($player, $name, $price, $count));
            }
        );
    }
}