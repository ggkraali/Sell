<?php

namespace ayd1ndemirci\SellSystem\form;

use ayd1ndemirci\SellSystem\event\PlayerSellItemEvent;
use ayd1ndemirci\SellSystem\manager\SellManager;
use dktapps\pmforms\CustomForm;
use dktapps\pmforms\CustomFormResponse;
use dktapps\pmforms\element\Label;
use dktapps\pmforms\element\Slider;
use dktapps\pmforms\element\Toggle;
use pocketmine\player\Player;

class SellHandSelectForm extends CustomForm
{

    /**
     * @param Player $player
     * @param \pocketmine\item\Item $item
     * @param mixed $price
     */
    public function __construct(\pocketmine\player\Player $player, \pocketmine\item\Item $item, mixed $price)
    {
        parent::__construct(
            "Elindeki Eşyayı Sat",
            [
                new Label("info0", "§eSatılacak eşya: §f§o".$item->getName()),
                new Label("info1", "§r§eTane Fiyatı: §f§o".$price." TL\n"),
                new Label("info2", "§r§eBu eşyadan elinde §f§o". $item->getCount()." §r§eadet var.\n"),
                new Toggle("toggle", "Hepsini Sat"),
                new Label("\n", "\n"),
                new Slider("slider", "Miktar Seç", 1, $item->getCount())
            ],
            function (Player $player, CustomFormResponse $response) use ($item, $price): void
            {
                $toggle = $response->getBool("toggle");
                $slider = $response->getFloat("slider");

                $itemCount = 0;
                $totalPrice = 0;

                if ($toggle) {
                    $itemCount = $item->getCount();
                }else $itemCount = $slider;

                $item->setCount($itemCount);
                if ($player->getInventory()->contains($item)) {
                    $totalPrice += $price * $itemCount;
                    $player->getInventory()->removeItem($item);

                    $event = new PlayerSellItemEvent($player, $item, $itemCount, $totalPrice);

                    $player->sendForm(new InformationForm("Sat", "§aElindeki §2". $itemCount."x ".$item->getName()." §aadlı eşyayı sattın. Kazanç: §2".SellManager::getNumberFormatter($totalPrice). " TL", "Tamam", "Kapat"));

                    SellManager::addMoney($player, $totalPrice);
                }else $player->sendMessage("§8» §cBu eşyadan envanterinde yeterince yok.");
            }
        );
    }
}