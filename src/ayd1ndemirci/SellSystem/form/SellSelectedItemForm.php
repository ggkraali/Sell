<?php

namespace ayd1ndemirci\SellSystem\form;

use ayd1ndemirci\SellSystem\event\PlayerSellItemEvent;
use ayd1ndemirci\SellSystem\manager\SellManager;
use dktapps\pmforms\CustomForm;
use dktapps\pmforms\CustomFormResponse;
use dktapps\pmforms\element\Input;
use dktapps\pmforms\element\Label;
use dktapps\pmforms\element\Slider;
use dktapps\pmforms\element\Toggle;
use pocketmine\item\LegacyStringToItemParser;
use pocketmine\item\StringToItemParser;
use pocketmine\player\Player;

class SellSelectedItemForm extends CustomForm
{

    /**
     * @param Player $player
     * @param mixed $name
     * @param mixed $price
     */
    public function __construct(Player $player, mixed $name, mixed $price, mixed $count)
    {
        $item = LegacyStringToItemParser::getInstance()->parse($name);
        $count = SellManager::getItemCount($player, $item);
        parent::__construct(
            $item->getName()." - Satışı",
            [
                new Label("info0", "§eSatılacak eşya: §f§o".$item->getName()),
                new Label("info1", "§r§eTane Fiyatı: §f§o".$price." TL\n"),
                new Label("info2", "§r§eBu eşyadan envanterinde §f§o".$count." §r§eadet var.\n"),
                new Toggle("toggle", "Hepsini Sat"),
                new Label("\n", "\n"),
                new Slider("slider", "Miktar Seç", 1, $count)
            ],
            function (Player $player, CustomFormResponse $response) use ($item, $price,$count): void
            {
                $toggle = $response->getBool("toggle");
                $slider = $response->getFloat("slider");

                $itemCount = 0;
                $totalPrice = 0;
                if ($toggle) {
                    $itemCount = $count;
                }else $itemCount = $slider;

                $item->setCount($itemCount);
                if ($player->getInventory()->contains($item)) {
                    $totalPrice += $price * $itemCount;
                    $player->getInventory()->removeItem($item);

                    $event = new PlayerSellItemEvent($player, $item, $count, $totalPrice);

                    SellManager::addMoney($event->getPlayer(), $event->getPrice());

                    $player->sendForm(new InformationForm("Sat", "§aBaşarılı bir şekilde §2".$event->getCount()."x ".$event->getItem()->getName()." §aadlı eşyayı satarak §2".SellManager::getNumberFormatter($event->getPrice())." TL §akazandın.", "Tamam", "Kapat"));
                }else $player->sendMessage("§8» §cBu eşyadan envanterinde yeterince yok.");
            }
        );
    }
}